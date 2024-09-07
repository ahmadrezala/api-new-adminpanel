<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Repository\DashboardRepository;
use Api\ApiResponse\Facades\ApiResponse;

class DashboardController extends Controller
{


    public function __construct(private DashboardRepository $dashboardRepository)
    {
    }

    public function salesChart()
    {

        $result = $this->dashboardRepository->dataSalesChart();

        return $result->ok
            ? ApiResponse::withData($result->data)->build()->response()
            : ApiResponse::withMessage('Something is wrong. try again later!')->withStatus(500)->build()->response();
    }
}
