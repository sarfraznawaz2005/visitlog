<?php

Route::group(
    [
        'namespace' => 'Sarfraznawaz2005\VisitLog\Http\Controllers',
        'prefix' => config('visitlog.route', 'visitlog')
    ],
    function () {
        Route::get('/', 'VisitLogController@index')->name('__visitlog__');
        Route::delete('delete_visitlog/{id}', 'VisitLogController@destroy')->name('__delete_visitlog__');
        Route::delete('delete_visitlog_all', 'VisitLogController@destroyAll')->name('__delete_visitlog_all__');
    }
);

