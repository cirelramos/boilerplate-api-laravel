<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel External Request
    |--------------------------------------------------------------------------
    |
    |
    */


    /*
     * get special values from header
     * example:
     */
    'get_special_values_from_header' => [
        'authorization' => 'authorization',
    ],


    /*
     * get special values from request
     * example:
     */
    'get_special_values_from_request' => [
        'ip_client' => 'ip_client',
        'user_id' => 'user_id',
    ],


    /*
     * view/template alert email
     * example:
     */
    'view-alert-email' => "emails.alert_mails",

    /*
     * cache tag name
     * example:
     */
    'cache-tag-name' => "CACHE_GROUP_NOTIFICATION",


    /*
     * cache tag time
     * example:
     */
    'cache-tag-time' => 3600,

    /*
     * cache name
     * example:
     */
    'cache-name' => "error_notification_",

    /*
     * mail recipient notification error
     * example:
     */
    'mail-recipient' => env("MAIL_ALERTS_USERS"),

    /*
     * default channel slack
     * example:
     */
    'default-channel-slack' => env("LOG_SLACK_WEBHOOK_URL"),
];

