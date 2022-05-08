<?php

namespace App\Core\Teams\Services;

use App\Core\Teams\Models\Country;

/**
 * Class GetCountryByCodeCountryService
 * @package App\Services
 */
class GetCountryByCodeCountryService
{

    /**
     * @param $codeCountry
     * @return array
     */
    public function execute($codeCountry): array
    {
        return $this->getCountryByCountryCode($codeCountry);

    }

    /**
     * @param $codeCountry
     * @return array
     */
    private function getCountryByCountryCode($codeCountry): array
    {
        return Country::query()
            ->where('countries.country_Iso', $codeCountry)
            ->getFromCache([ 'country_id' ])
            ->toArray();
    }
}
