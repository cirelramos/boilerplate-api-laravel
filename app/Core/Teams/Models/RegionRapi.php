<?php

namespace App\Core\Teams\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Cirelramos\Cache\Models\CacheModel;

class RegionRapi extends CacheModel
{

    use SoftDeletes;

    protected $table      = 'regions';
    protected $primaryKey = 'id_region';

    public const TAG_CACHE_MODEL = 'TAG_CACHE_REGION_RAPI_';

    protected $fillable = [
        'name',
    ];
    protected $guarded  = [];

    public function countriesRegions()
    {
        return $this->hasMany(CountryRegionPivot::class, 'id_region', 'id_region')
            ->whereNull('country_region.deleted_at');
    }
}
