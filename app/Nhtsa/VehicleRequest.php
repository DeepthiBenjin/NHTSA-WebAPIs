<?php
namespace App\Nhtsa;
/**
 * Class VehicleRequest
 * @package App\Nhtsa
 */
class VehicleRequest
{
    use ApiClient;

    /**
     * process request
     * @param int $year
     * @param string $manufacturer
     * @param string $model
     * @param bool $withRating
     * @return array
     * @throws \Exception
     */
    public function process(int $year, string $manufacturer, string $model, bool $withRating = false)
    {
        $this->validate($year, $manufacturer, $model);
        $body = $this->availableVehicles($year, $manufacturer, $model);
        return $this->buildResponse($body, $withRating);
    }

    /**
     * Validate Request
     * @param int $year
     * @param string $manufacturer
     * @param string $model
     * @throws \Exception
     */
    private function validate(int $year, string $manufacturer, string $model)
    {
        if (!preg_match('/[0-9]{4}$/D', $year)) {
            throw new \Exception("Invalid Year");
        }

        if (empty($manufacturer)) {
            throw new \Exception("Manufacturer can't is blank");
        }
        if (empty($model)) {
            throw new \Exception("Model can't is blank");
        }
    }

    /**
     * Request to get available Vehicles by year, manufacturer and model
     * @param int $year
     * @param string $manufacturer
     * @param string $model
     * @return string
     */
    private function availableVehicles(int $year, string $manufacturer, string $model)
    {
        $query = sprintf('modelyear/%u/make/%s/model/%s', $year, $manufacturer, $model);
        $requestUrl = sprintf($this->endPoint, $query);
        return $this->send($requestUrl);
    }

    /**
     * build response after receiving request
     * @param $body
     * @param $withRating
     * @return array
     */
    private function buildResponse($body, $withRating)
    {
        $result = \GuzzleHttp\json_decode($body);
        $foundVehicle = [];
        $foundVehicle['Count'] = 0;
        $foundVehicle['Results'] = [];

        $rid = 0;

        if (isset($result->Count) && $result->Count > 0) {
            $foundVehicle['Count'] = $result->Count;
            foreach ($result->Results as $rowVehicle) {
                if ($withRating === true) {
                    $resultVehicleRating = \GuzzleHttp\json_decode($this->safetyRatingsByVehicleId($rowVehicle->VehicleId));

                    if (isset($resultVehicleRating->Count) && $resultVehicleRating->Count > 0) {
                        $foundVehicle['Results'][$rid]['CrashRating'] = $resultVehicleRating->Results[0]->OverallRating;
                    }
                }
                $foundVehicle['Results'][$rid]['Description'] = $rowVehicle->VehicleDescription;
                $foundVehicle['Results'][$rid]['VehicleId'] = $rowVehicle->VehicleId;
                $rid++;
            }

        }
        return $foundVehicle;
    }

    /**
     * Request to get Vehicle CrashRating by vehicleId
     * @param int $vehicleId
     * @return string
     */
    private function safetyRatingsByVehicleId(int $vehicleId)
    {
        //VehicleId/<VehicleId>
        $query = sprintf('VehicleId/%u', $vehicleId);
        $requestUrl = sprintf($this->endPoint, $query);
        return $this->send($requestUrl);
    }
}