<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Nhtsa\VehicleRequest;

/**
 * Class VehicleController
 * @package App\Http\Controllers\API
 */
class VehicleController extends Controller
{
    /**
     * JSON response of vehicle report.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $withRating = $request->query('withRating');
            $withRating = ($withRating == 'true') ? true : false;

            if ($request->isMethod('post')) {
                $requestVars = \GuzzleHttp\json_decode($request->getContent());

                $modelYear = $requestVars->modelYear;
                $manufacturer = $requestVars->manufacturer;
                $model = $requestVars->model;

                // Disable withRating on post as it's not in requirement
                $withRating = false;
                unset($requestVars);

            } else {
                $modelYear = $request->modelYear;
                $manufacturer = $request->manufacturer;
                $model = $request->model;
            }

            $vehicleRequest = new VehicleRequest();
            $response = $vehicleRequest->process($modelYear, $manufacturer, $model, $withRating);
            return $response;
        } catch (\Exception $exception) {
            return ['Count' => 0, 'Results' => []];
        }
    }

}
