<?php

namespace App\Core\Players\Collections;

use App\Core\Base\Collections\CoreResourceCollection;
use App\Core\Players\Resources\PlayerResource;

/**
 *
 */
class PlayersCollection extends CoreResourceCollection
{

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = PlayerResource::class;
}
