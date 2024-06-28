<?php
namespace App\Services\PaymentGateway;

class Zibal extends Payment
{


    public function send($request , $addressId)
    {

        $totalAmount = 0;
        $deliveryAmount = 0;

        $checkCart = $this->checkCart($request->items);
        if (array_key_exists('error', $checkCart)) {
            return ['error' => $checkCart];
        }


        $payingAmount = $totalAmount + $deliveryAmount;

        $amounts = [
            'totalAmount' => $totalAmount,
            'deliveryAmount' => $deliveryAmount,
            'payingAmount' => $payingAmount,
        ];

        $merchant = env('ZIBAL_IR_API_KEY');
        $amount = $payingAmount . '0';
        $mobile = "شماره موبایل";
        $description = "توضیحات";
        $callbackUrl = env('ZIBAL_IR_CALLBACK_URL');
        $result = $this->sendRequest($merchant, $amount, $callbackUrl, $mobile, $description);
        return $result = json_decode($result);
        if ($result->result == 100) {
            $createOrder = parent::createOrder($addressId, $amounts, $result->trackId, 'pay');
            return $request;
            $go = "https://gateway.zibal.ir/start/$result->trackId";
            return ApiResponse::withAppends(['url' => $go])->withStatus(422)->build()->response();

        } else {
            return ApiResponse::withMessage('پرداخت با مشکل روبرو شد')->withStatus(422)->build()->response();
        }
    }

    public function verify(PaymentVerifyApiRequest $request)
    {


        $merchant = env('ZIBAL_IR_API_KEY');
        $trackId = $request->trackId;
        $result = json_decode($this->verifyRequest($merchant, $trackId));
        if (isset($result->status)) {
            if ($result->status == 1) {
                if (Transaction::where('trans_id', $result->transId)->exists()) {
                    return ApiResponse::withMessage('این تراکنش قبلا توی سیستم ثبت شده است')->withStatus(422)->build()->response();
                }
                OrderController::update($trackId, $result->transId);
                return ApiResponse::withMessage('تراکنش با موفقیت انجام شد')->withStatus(200)->build()->response();
            } else {
                return ApiResponse::withMessage('تراکنش با خطا مواجه شد')->withStatus(422)->build()->response();
            }
        } else {
            if ($request->status == 0) {
                return ApiResponse::withMessage('تراکنش با خطا مواجه شد')->withStatus(422)->build()->response();
            }
        }
    }

    public function sendRequest($merchant, $amount, $callbackUrl, $mobile = null, $description = null)
    {
        return $this->curl_post('https://gateway.zibal.ir/v1/request', [
            'merchant' => $merchant,
            'amount' => $amount,
            'callbackUrl' => $callbackUrl,
            'mobile' => $mobile,
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



    public function checkCart($items)
    {
        if (!$items) {
            return ['error' => 'سبد خرید شما خالی می باشد'];
        }
        foreach ($items as $item) {
            $variation = ProductVariation::find($item->attributes->id);

            $price = $variation->is_sale ? $variation->sale_price : $variation->price;

            if ($item->price != $price) {
                return ['error' => 'قیمت محصول تغییر پیدا کرد'];
            }

            if ($item->quantity > $variation->quantity)
                return ['error' => 'تعداد محصول تغییر پیدا کرد'];
        }

        return ['success' => 'success!'];
    }

}
