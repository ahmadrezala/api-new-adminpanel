<?php

namespace App\Repository;

use App\Base\ServiceResult;
use App\Base\ServiceWrapper;
use App\Models\Order;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{

    public function __construct(private Order $order)
    {

    }



    public function dataSalesChart(): ServiceResult
    {
        return app(ServiceWrapper::class)(function () {

            $endDate = now();
            $startDate = $endDate->copy()->subMonths(12);

            $sales = $this->order::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(paying_amount) as total'))
                ->where('payment_status', 1)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->get();


            $salesByMonth = [];

            foreach ($sales as $sale) {

                $shamsiDate = Jalalian::fromDateTime($sale->date)->format('Y-m-d H:i:s');


                if (!isset($salesByMonth[$shamsiDate])) {
                    $salesByMonth[$shamsiDate] = 0;
                }

                $salesByMonth[$shamsiDate] += $sale->total;

            }




            $data = [];
            $i = 0;

            foreach ($salesByMonth as $month => $total) {


                $monthName = Jalalian::fromFormat('Y-m-d H:i:s', $month)->format('%B %y');

                $data[$i]['name'] = $monthName;
                $data[$i]['data'] = $total;
                $i++;
            }



            return $data;

        });

    }


}



