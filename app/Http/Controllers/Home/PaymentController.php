<?php

namespace App\Http\Controllers\Home;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\PaymentGateway\Zibal;
use Api\ApiResponse\Facades\ApiResponse;
use App\Http\ApiRequests\Home\Payment\PaymentSendApiRequest;
use App\Http\ApiRequests\Home\Payment\PaymentVerifyApiRequest;


class PaymentController extends Controller
{

    public function send(Request $request)
    {


        $checkItems = $this->checkItems($request->items, $request->coupon);
        if (array_key_exists('error', $checkItems)) {
            return ApiResponse::withMessage($checkItems['error'])->withStatus(422)->build()->response();
        }

        $merchant = env('ZIBAL_IR_API_KEY');
        $amount = $checkItems['amount']['amount'] . '0';
        $mobile = "شماره موبایل";
        $factorNumber = "شماره فاکتور";
        $description = "توضیحات";
        $callbackUrl = env('ZIBAL_IR_CALLBACK_URL');

        $result = $this->sendRequest($merchant, $amount, $callbackUrl, $mobile, $factorNumber, $description);

        $result = json_decode($result);
        if ($result->result == 100) {
            $this->createOrder($request->address_id, $checkItems['amount'], $result->trackId, $request->payment_method, $request->items);



            $go = "https://gateway.zibal.ir/start/$result->trackId";
            dd($go);
            return ApiResponse::withAppends(['url' => $go])->withStatus(422)->build()->response();
        } else {
            return ApiResponse::withMessage($result->message)->withStatus(422)->build()->response();
        }
    }

    public function verify(Request $request)
    {

        // return $request['trackId'];

        $merchant = env('ZIBAL_IR_API_KEY');
        $trackId = $request->trackId;
        $result = json_decode($this->verifyRequest($merchant, $trackId));
        if (isset($result->status)) {
            if ($result->status == 1) {
                // if (Transaction::where('ref_id', $result->refNumber)->exists()) {
                //     return ApiResponse::withMessage('این تراکنش قبلا توی سیستم ثبت شده است')->withStatus(422)->build()->response();

                // }
               return $this->updateOrder($request['trackId'], $result->refNumber);

                // return ApiResponse::withMessage('تراکنش با موفقیت انجام شد')->withStatus(200)->build()->response();

            } else {
                return ApiResponse::withMessage('تراکنش با خطا مواجه شد')->withStatus(422)->build()->response();

            }
        } else {
            if ($request->status == 0) {
                return ApiResponse::withMessage('تراکنش با خطا مواجه شد')->withStatus(422)->build()->response();

            }
        }
    }

    public function sendRequest($merchant, $amount, $callbackUrl, $mobile = null, $factorNumber = null, $description = null)
    {
        return $this->curl_post('https://gateway.zibal.ir/v1/request', [
            'merchant' => $merchant,
            'amount' => $amount,
            'callbackUrl' => $callbackUrl,
            'mobile' => $mobile,
            'orderId' => $amount,
            'factorNumber' => $factorNumber,
            'description' => $description,
        ]);
    }

    function verifyRequest($merchant, $trackId)
    {
        return $this->curl_post('https://gateway.zibal.ir/v1/verify', [
            'merchant' => $merchant,
            'trackId' => $trackId,
        ]);
    }


    public function curl_post($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }


    public function createOrder($addressId, $amounts, $token, $gateway_name, $items)
    {


        try {
            DB::beginTransaction();
            // auth()->id();
            $order = Order::create([
                'user_id' => '1',
                'address_id' => $addressId,
                'coupon_id' => $amounts['coupon_id'],
                'total_amount' => $amounts['total_amount'],
                'delivery_amount' => $amounts['delivery_amount'],
                'coupon_name' => $amounts['coupon'],
                'paying_amount' => $amounts['amount'],
                'payment_type' => 'online',
            ]);


            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['attributes']['product_id'],
                    'product_variation_id' => $item['attributes']['id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => ($item['quantity'] * $item['price'])
                ]);
            }

            Transaction::create([
                'user_id' => '1',
                'order_id' => $order->id,
                'amount' => $amounts['amount'],
                'token' => $token,
                'gateway_name' => $gateway_name
            ]);
         
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex->getMessage());
            return ['error' => $ex->getMessage()];
        }

        return [
            'success' => 'success!',
        ];
    }

    public function updateOrder($trackId, $refNumber)
    {

        try {
            DB::beginTransaction();

            $transaction = Transaction::where('token', $trackId)->firstOrFail();

            $transaction->update([
                'status' => 1,
                'ref_id' => $refNumber
            ]);

            $order = Order::findOrFail($transaction->order_id);
            $order->update([
                'payment_status' => 1,
                'status' => 1
            ]);


            foreach ($order->orderItems as $item) {
                $variation = ProductVariation::find($item['product_variation_id']);
                $variation->update([
                    'quantity' => $variation->quantity - $item['quantity']
                ]);
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return ['error' => $ex->getMessage()];
        }

        return ['success' => 'success!'];
    }





    function cartTotalDeliveryAmount($items)
    {
        $cartTotalDeliveryAmount = 0;
        foreach ($items as $item) {
            $cartTotalDeliveryAmount += $item->delivery_amount;
        }

        return $cartTotalDeliveryAmount;
    }

    public function checkCart($items)
    {

        if (empty($items)) {

            return ['error' => 'سبد خرید شما خالی می باشد'];
        }

        foreach ($items as $item) {

            $variation = ProductVariation::find($item['attributes']['id']);
            $product = Product::find($item['attributes']['product_id']);
            if (!$variation->product_id == $product->id) {
                return ['error' => 'اطلاعات محصول درست نیست'];
            }
            $price = $variation->is_sale ? $variation->sale_price : $variation->price;

            if ($item['price'] != $price) {
                return ['error' => 'قیمت محصول تغییر پیدا کرد'];
            }
            if ($item['delivery_amount'] != $product->delivery_amount) {
                return ['error' => 'هزینه مسیر تغییر پیدا کرد'];
            }
            if ($item['attributes']['sale_price'] != $variation->sale_price) {
                return ['error' => 'این محصول تخفیف ندارد'];
            }

            if ($item['quantity'] > $variation->quantity)
                return ['error' => 'تعداد محصول تغییر پیدا کرد'];
        }

        return ['success' => 'success!'];
    }



    public function checkItems($items, $coupon)
    {

        $checkCart = $this->checkCart($items);
        if (array_key_exists('error', $checkCart)) {

            return ['error' => $checkCart['error']];
        } else {
            $peyment = 0;
            $total_amount = 0;
            $delivery_amount = 0;
            foreach ($items as $item) {
                $total_amount += $item['attributes']['sale_price'] ? $item['attributes']['sale_price'] : $item['attributes']['price'];
                $total_amount += $item['delivery_amount'];
                $total_amount *= $item['quantity'];
                $delivery_amount += $item['delivery_amount'];
                $delivery_amount *= $item['quantity'];

            }
            if ($coupon) {

                $coupon = $this->checkCodeCoupon($coupon);
                if (array_key_exists('error', $coupon)) {
                    return ['error' => $coupon['error']];

                } elseif ($coupon['coupon']->type == 'amount') {
                    $peyment = $total_amount - $coupon['coupon']->amount;
                } else {

                    $peyment -= (($total_amount * $coupon['coupon']->percentage) / 100) > $coupon['coupon']->max_percentage_amount ? $coupon['coupon']->max_percentage_amount : (($total_amount * $coupon['coupon']->percentage) / 100);
                }
            }
        }


        return [
            'success' => 'ok',
            'amount' => [

                'amount' => $peyment,
                'total_amount' => $total_amount,
                'delivery_amount' => $delivery_amount,
                'coupon' => $coupon ? $coupon['coupon']['name'] : null,
                'coupon_id' => $coupon ? $coupon['coupon']['id'] : null
            ]

        ];
    }


    function checkCodeCoupon($code)
    {
        $coupon = Coupon::where('code', $code)->where('expired_at', '>', Carbon::now())->first();

        if ($coupon == null) {
            return ['error' => 'کد تخفیف وارد شده وجود ندارد'];
        }

        if (Order::where('user_id', auth()->id())->where('coupon_id', $coupon->code)->where('payment_status', 1)->exists()) {
            return ['error' => 'شما قبلا از این کد تخفیف استفاده کرده اید'];
        }

        return [
            'success' => 'کد تخفیف برای شما ثبت شد',
            'coupon' => $coupon
        ];
    }

    public function checkCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        if (!auth()->check()) {
            return ApiResponse::withMessage('برای استفاده از کد تخفیف نیاز هست ابتدا وارد وبسایت شوید')->withStatus(422)->build()->response();

        }

        $result = $this->checkCodeCoupon($request->code);

        if (array_key_exists('error', $result)) {
            return ApiResponse::withMessage($result['error'])->withStatus(422)->build()->response();

        } else {
            return ApiResponse::withMessage($result['success'])->withStatus(422)->build()->response();

        }
    }



}
