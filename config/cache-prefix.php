<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Key name prefix cache redis
    |--------------------------------------------------------------------------
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Key Terms
    |--------------------------------------------------------------------------
    |
    */
    'terms:import'            => 'terms-import',
    'terms:import-progress'   => 'terms-import-progress',
    'terms:import-progress2'  => 'terms-import-progress2',
    'terms:export-progress'   => 'terms-export-progress',
    'terms:template-progress' => 'terms-template-progress',
    'terms:confirm-import'    => 'terms-confirm-import',

    /* Save the array of terms in redis for validations */
    'terms:change-value-import' => 'terms-change-value-import',

    /* Save the list of terms redis */
    'terms:cache-list'          => 'terms-cache-list',


];
