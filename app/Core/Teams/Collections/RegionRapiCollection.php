<?php

namespace App\Core\Teams\Collections;

use App\Core\Base\Collections\CoreResourceCollection;
use App\Core\Teams\Resources\RegionRapiResource;

class RegionRapiCollection extends CoreResourceCollection
{

    public $collects = RegionRapiResource::class;
}
