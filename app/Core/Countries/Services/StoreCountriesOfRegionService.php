<?php

namespace App\Core\Countries\Services;

use App\Core\Countries\Models\CountryRegionPivot;
use App\Core\Countries\Models\RegionRapi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StoreCountriesOfRegionService
{

    public function execute(RegionRapi $regionRapi, Request $request)
    {
        $countries = $request->input('countries');
        $countries = collect($countries);

        $countries = $countries->map($this->mapSetRegion($regionRapi));

        CountryRegionPivot::insert($countries->toArray());
    }

    private function mapSetRegion(RegionRapi $regionRapi): callable
    {
        return static function ($region, $key) use ($regionRapi) {

            $region[ 'id_region']  = $regionRapi->id_region;
            $region[ 'created_at'] = Carbon::now();

            return $region;
        };
    }

}
