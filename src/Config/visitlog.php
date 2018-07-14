<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Route
    |--------------------------------------------------------------------------
    |
    | Route where visitlog will be available in your app.
    |
    */

    'route' => 'visitlog',

    /*
    |--------------------------------------------------------------------------
    | IP to Location Service
    |--------------------------------------------------------------------------
    |
    | If "true", it will use http://freegeoip.net to get and save more information about visitor like
    | Country, Region, City, Zip, Timezone,Location Coordinates. If "false", it will only save IP, OS
    | and Browser info.
    |
    | Note: For requests from same IP, it will be cached so no further request is made to http://freegeoip.net
    |
    */

    'iptolocation' => true,
    'token' => 'PASTE_YOUR_TOKEN', // get your token here: https://ipstack.com/

    /*
    |--------------------------------------------------------------------------
    | Cache Requests
    |--------------------------------------------------------------------------
    |
    | If "true", it will cache the results for same IP so no more request will be made to
    | http://freegeoip.net
    |
    */

    'cache' => true,

    /*
    |--------------------------------------------------------------------------
    | Visit Log Type
    |--------------------------------------------------------------------------
    |
    | If "true", it will only log unique visits meaning same IP will not be logged again. If "false",
    | same IP will be logged repeatedly on each visit.
    |
    */

    'unique' => true,

    /*
    |--------------------------------------------------------------------------
    | Log Authenticaed User
    |--------------------------------------------------------------------------
    |
    | If "true", it will also log id and name of logged in user. The "user_name_fields" should be set
    | to name fields from user table in database.
    |
    */

    'log_user' => true,
    'user_name_fields' => ['first_name', 'last_name'],

    /*
    |--------------------------------------------------------------------------
    | Visit Log Page
    |--------------------------------------------------------------------------
    |
    | visitlog_page: If "true", a page at url http://yourapp.com/visitlog will show all visit logs. If "false",
    | 404 will be shown instead.
    |
    | delete_log_button: If "true", a delete icon will be added against each log entry.
    |
    | delete_all_logs_button: If "true", a button to delete all logs will be shown on visit log page.
    |
    | http_authentication: If "true", the visit log page can be viewed by any user who provides
    | correct email and password (eg all app users).
    |
    */

    'visitlog_page' => true,
    'delete_log_button' => false,
    'delete_all_logs_button' => false,
    'http_authentication' => false
);
