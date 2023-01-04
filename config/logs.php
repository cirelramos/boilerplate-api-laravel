<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel logs
    |--------------------------------------------------------------------------
    |
    |
    */

    /*
     * exclude query by words
     * example:
     *
    'exclude_log_query_by_words' => [
        'token',
        'password',
    ],
     */
    'exclude_log_query_by_words' => [
        'oauth_access_tokens',
        'oauth_clients',
    ],

    /*
     * if you want logs of sql query
     *
     */
    'query_log_is_active' => true,


    /*
     * exclude logs by path of endpoints
     * example:
     */
    'exclude_log_by_endpoint' => [
        '/api/documentation',
        '/docs/asset',
        '/docs/api-docs.json',
    ],

    /*
     * parameters to exclude of the header
     * example:
     */
    'exclude_parameters_of_header' => [
        'password'
    ],


    /*
     * parameters to exclude of the request
     * example:
     */
    'exclude_parameters_of_request' => [
        'password'
    ],

    /*
     * get global special values from request
     * example:
     */
    'get_global_special_values_from_request' => [
        'job_name_and_timeline' => 'job_name_and_timeline',
    ],


    /*
     * get special values from request
     * example:
     */
    'get_special_values_from_request' => [
        'client_domain' => 'client_domain',
        'user_id' => 'user_id',
        'id_user_external' => 'id_user_external',
    ],
];
