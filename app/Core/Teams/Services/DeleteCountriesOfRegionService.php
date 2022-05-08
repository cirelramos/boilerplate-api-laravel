<?php

namespace App\Core\Teams\Services;


use App\Core\Teams\Models\CountryRegionPivot;
use App\Core\Teams\Models\RegionRapi;

class DeleteCountriesOfRegionService
{

    public function execute(RegionRapi $regionRapi)
    {
        CountryRegionPivot::where('id_region', $regionRapi->id_region)->delete();
    }
}
