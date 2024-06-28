<?php
namespace App\Base;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Debug\ExceptionHandler;


class ServiceWrapper
{


    public function __invoke(\Closure $action, \Closure $reject = null)
    {

        try {
            DB::beginTransaction();
            $actionResult = $action();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            !is_null($reject) && $reject();
            app()[ExceptionHandler::class]->report($th);
            dd($th->getMessage());
            return new ServiceResult(false, $th->getMessage());

        }

        return new ServiceResult(true, $actionResult);

    }
}
