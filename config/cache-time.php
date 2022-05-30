<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Time session cache redis
    |--------------------------------------------------------------------------
    |
    */
    /*
    |--------------------------------------------------------------------------
    | Key terms
    |--------------------------------------------------------------------------
    |
    */
    'terms:import'              => 300000,
    'terms:import-progress'     => 200000,
    'terms:export-progress'     => 200000,
    'terms:template-progress'   => 200000,
    'terms:confirm-import'      => 300000,

    /* Save the array of products in redis for validations */
    'terms:change-value-import' => 50000,

    /* Save the list of products redis */
    'terms:cache-list'          => 150000,

];
