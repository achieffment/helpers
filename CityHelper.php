<?php

namespace chieff\helpers;

use chieff\helpers\Helper;

use morphos\Russian\GeographicalNamesInflection;
use Dadata\DadataClient;

class CityHelper {

    /**
     * @var string
     */
    private string $dadata_token = '';

    /**
     * @var string
     */
    private string $dadata_secret = '';

    /**
     * @var \Dadata\DadataClient
     */
    private \Dadata\DadataClient $dadata;

    /**
     * @var string
     */
    public string $city;

    /**
     * @param string $cityDefault
     * @param string $token
     * @param string $secret
     * @throws \Exception
     */
    public function __construct(string $cityDefault, string $token, string $secret)
    {
        if (!$cityDefault)
            throw new \Exception('Default city is empty!');

        if (!$token || !$secret)
            throw new \Exception('Token or secret are empty!');

        $this->city = $cityDefault;

        $this->dadata_token = $token;
        $this->dadata_secret = $secret;

        $this->dadata = new DadataClient($this->dadata_token, $this->dadata_secret);
    }

    /**
     * @param bool $robotCheck
     * @param bool $morphUse
     * @param string $morphCase
     * @return string
     */
    public function getCity(bool $robotCheck = true, bool $morphUse = true, string $morphCase = 'предложный') {
        if (
            (
                !$robotCheck ||
                (
                    $robotCheck &&
                    !Helper::isRobot()
                )
            ) &&
            ($ip = Helper::getIp()) &&
            ($ip != '127.0.0.1')
        ) {
            try {
                $result = $this->dadata->iplocate($ip);
                if (
                    $result &&
                    is_array($result) &&
                    isset($result["data"]) &&
                    is_array($result["data"]) &&
                    isset($result["data"]["city"]) &&
                    $result["data"]["city"]
                )
                    $this->city = $result["data"]["city"];
            } catch (\Exception $e) {

            }
        }

        if ($morphUse)
            $this->city = GeographicalNamesInflection::getCase($this->city, $morphCase);

        return $this->city;
    }

}