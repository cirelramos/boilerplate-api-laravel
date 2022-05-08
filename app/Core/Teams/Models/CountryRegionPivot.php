<?php

namespace App\Core\Teams\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryRegionPivot extends Model
{

    use SoftDeletes;

    protected $table = 'country_region';

    protected $fillable = [
        'id_country',
        'id_region',
    ];


    public function country()
    {
        return $this->belongsTo(Country::class, 'id_country', 'country_id');
    }
}
