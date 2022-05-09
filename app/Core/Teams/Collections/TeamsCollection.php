<?php

namespace App\Core\Teams\Collections;

use App\Core\Base\Collections\CoreResourceCollection;
use App\Core\Teams\Resources\TeamResource;

/**
 *
 */
class TeamsCollection extends CoreResourceCollection
{

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = TeamResource::class;
}
