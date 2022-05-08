<?php

namespace App\Core\Teams\Resources;

use App\Core\Base\Traits\UtilsFormatText;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CountryResource
 * @package App\Http\Resources
 */
class CountryResource extends JsonResource
{
    use UtilsFormatText;

    public function toArray($request)
    {
        return [
            'identifier' => (integer) $this->country_id,
            'iso'        => (string) $this->convertTextCharset($this->country_Iso),
            'name'       => (string) $this->convertTextCharset($this->name),
        ];
    }
}
