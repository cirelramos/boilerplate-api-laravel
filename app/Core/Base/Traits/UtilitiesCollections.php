<?php

namespace App\Core\Base\Traits;

trait UtilitiesCollections
{
    public function rejectValuesNullTransform(): callable
    {
        return function ($item) {
            return empty($item);
        };
    }

    public function rejectKeysNullTransform(): callable
    {
        return function ($item, $key) {
            return empty($key);
        };
    }

    public function rejectAttributeOfCollectionWithValueNullTransform(string $attribute): callable
    {
        return function ($item, $key) use ($attribute) {
            $item = (array)$item;
            if (array_key_exists($attribute, $item)) {
                return empty($item[ $attribute ]);
            }

            return false;
        };
    }

    public function rejectAttributeOfCollectionTransform(string $attribute): callable
    {
        return function ($item, $key) use ($attribute) {
            $item = (array)$item;

            return array_key_exists($attribute, $item);
        };
    }

    public function mapChangeNameTransform(array $alias_key): callable
    {
        return function ($item, $key) use ($alias_key) {
            return $this->keysUnset($item, $alias_key);
        };
    }

    private function keysUnset($item, $alias_key)
    {
        $item = (array)$item;
        foreach ($alias_key as $load_key) {
            /*check exist key old in array*/
            if (array_key_exists('key', $load_key)) {
                $key_old = $load_key[ 'key' ];
                /*check exist new key in array*/
                if (array_key_exists('alias', $load_key)) {
                    $alias          = $load_key[ 'alias' ];
                    $item[ $alias ] = $item[ $key_old ];
                }
                /*check exist element old and unset*/
                if (array_key_exists($key_old, $item)) {
                    unset($item[ $key_old ]);
                }
            }
        }

        return $item;
    }

    public function mapChangeToCollectionTransform(): callable
    {
        return function ($item) {
            return collect($item);
        };
    }

    public function mapChangeArrayToObjectTransform(): callable
    {
        return function ($item) {
            return (object)$item;
        };
    }

    public function mapChangeObjectToArrayTransform(): callable
    {
        return function ($item, $key) {
            return (array)$item;
        };
    }

    private function mapRemoveAttributesTransform(array $attributes): callable
    {
        return function ($item, $key) use ($attributes) {
            $item = (array)$item;
            collect($attributes)->each($this->eachRemoveAttributeTransform($item));

            return $item;
        };
    }

    private function eachRemoveAttributeTransform(array &$arrayElements): callable
    {
        return function ($item, $key) use (&$arrayElements) {
            unset($arrayElements[ $item ]);
        };
    }

    private function rejectItemsWithAttributeRemoveTrueTransform(): callable
    {
        return static function ($item) {
            return empty($item[ 'remove' ]) === false;
        };
    }

    private function mapSetItemRemoveToFalseTransform(): callable
    {
        return function ($item, $key) {
            $item[ 'remove' ] = 0;

            return $item;
        };
    }
}
