<?php

namespace App\Core\Teams\Services;

use Cirelramos\Cache\Services\GetCacheService;
use Cirelramos\Cache\Services\SetCacheService;
use App\Core\Teams\Models\Country;

/**
 * Class GetCountriesService
 * @package App\Core\Teams\Services
 */
class GetCountriesService
{
    /**
     * @param $global
     * @return array|mixed
     * @throws \Exception
     */
    public function execute($global = false)
    {
        $customKey = "index_countries".$global;
        $dataCache = GetCacheService::execute($customKey);
        if (empty($dataCache) === false) {
            return $dataCache;
        }

        $countries = Country::query();

        $countries = $countries->getFromCache();


        SetCacheService::execute($customKey, $countries, []);

        return $countries;
    }

}
