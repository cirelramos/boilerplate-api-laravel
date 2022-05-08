<?php

namespace App\Core\Teams\Models;

use Cirelramos\Cache\Models\CacheModel;

class Country extends CacheModel
{
    public    $connection = 'mysql_external';
    public    $timestamps = false;
    protected $guarded    = [];
    protected $table      = 'countries';
    protected $primaryKey = 'country_id';
    protected $fillable   = [
        'country_id',
        'country_Iso',
        'country_name_en',
        'country_name_es',
        'country_name_pt',
    ];

    public function country_info()
    {
        return $this->hasOne(CountryInfo::class, 'country_id', 'country_id');
    }

    public function getNameAttribute()
    {
        $name = 'country_name_' . app()->getLocale();

        return $this->$name ? $this->$name : $this->country_name_en;
    }
}
