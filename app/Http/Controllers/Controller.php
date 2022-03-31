<?php

namespace App\Http\Controllers;

use App\Traits\GeneralFunctions;
use App\Traits\OpenpayMethods;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Openpay;
use OpenpayApiError;
use OpenpayApiRequestError;
use OpenpayApiResourceBase;
use OpenpayApiTransactionError;


setlocale(LC_ALL,'es_ES', 'esp_esp');

class Controller extends BaseController
{
	#Declare a middleware in the construct, so we can access to the current user!
	function __construct() {
        date_default_timezone_set('America/Mexico_City');
        $this->puntos_min = 33;
        $this->summer = date('I');
        $this->actual_date = date('Y-m-d');
        $this->actual_month = date('Y-m');
        $this->actual_datetime = date('Y-m-d H:i:s');
        $this->app_id = "79c8d96e-aca6-4101-bed6-f8ce88731056";
        $this->app_key = "MjU0OWQ4ZjUtNGI3Ni00NTIwLWFkOGMtOTExN2I3Y2U2ZGRk";
        $this->app_icon = asset("img/icon_customer.png");

        Openpay::setId('mkzhdyejtxxkwiqxxaht');
        Openpay::setApiKey('sk_6570b404a283496e9c42ceb3e2143fd6');
        Openpay::setProductionMode(FALSE);
        $this->openpay = Openpay::getInstance();
        
        $this->middleware(function ($request, $next) {
            $this->current_user = auth()->user();

            return $next($request);
        });
	}
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, GeneralFunctions, OpenpayMethods;
}
