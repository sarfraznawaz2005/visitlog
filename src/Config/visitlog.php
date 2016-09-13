<?php

return array(

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

    'iptolocation' => false,

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
    | If "true", a page at url http://yourapp.com/visitlog will show all visit logs. If "false",
    | 404 will be shown instead.
    |
    */

    'visitlog_page' => true,
);
