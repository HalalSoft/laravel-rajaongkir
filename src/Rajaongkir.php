<?php


namespace Halalsoft\LaravelRajaongkir;

use App\Exceptions\CustomException;

class Rajaongkir
{
    protected $method;
    protected $parameters;
    protected $data;
    protected $endPointAPI;
    protected $apiKey;

    public function __construct()
    {
        $this->endPointAPI = env('RAJAONGKIR_API_URL', 'https://pro.rajaongkir.com/api');
        $this->apiKey      = env('RAJAONGKIR_API_KEY', '');
    }

    public static function getProvinces()
    {
        $data = (new self)->getData("province");

        return $data;
    }

    public static function getCities($province_id = 0)
    {
        $param = [];
        if ($province_id) {
            $param["province"] = $province_id;
        }
        $data = (new self)->getData("city", $param);

        return $data;
    }

    public static function getSubdistricts($city_id = 1)
    {
        $param         = [];
        $param["city"] = $city_id;

        $data = (new self)->getData("subdistrict", $param);

        return $data;
    }

    public static function getShipping($attributes)
    {
        $data = (new self)->getData("cost", $attributes)[0];

        return $data;
    }

    public static function track($attributes)
    {
        $data = (new self)->getData("waybill", $attributes);

        return $data;
    }

    public static function getShippings($attributes)
    {
        $data = (new self)->getData("cost", $attributes);

        return $data;
    }

    protected function getData($method = "", $params = [])
    {
        $curl    = curl_init();
        $options = [
            CURLOPT_URL            => $this->endPointAPI."/".$method."?".http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "key: ".$this->apiKey,
            ],
        ];


        if ($method == "cost" || $method == "waybill") {
            $options[CURLOPT_CUSTOMREQUEST] = "POST";
            $options[CURLOPT_POSTFIELDS]    = http_build_query($params);
            $options[CURLOPT_HTTPHEADER]    = [
                "content-type: application/x-www-form-urlencoded",
                "key: ".$this->apiKey,
            ];
        }
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new CustomException($err, 1);
        } else {
            $data = json_decode($response, true);
            $code = $data['rajaongkir']['status']['code'];
            if ($code == 400) {
                throw new CustomException("Rajaongkir error!! ".$data['rajaongkir']['status']['description'], 400);
            } else {
                return isset($data['rajaongkir']['results']) ? $data['rajaongkir']['results'] : $data['rajaongkir']['result'];
            }
        }
    }


}