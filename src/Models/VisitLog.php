<?php

namespace Sarfraznawaz2005\VisitLog\Models;

use Illuminate\Database\Eloquent\Model;

class VisitLog extends Model
{
    protected $table = 'visitlogs';

    protected $fillable = [
        'ip',
        'browser',
        'os',
        'user_id',
        'user_name',
        'country_code',
        'country_name',
        'region_name',
        'city',
        'zip_code',
        'time_zone',
        'latitude',
        'longitude',
    ];

    # global scope that will be applied to all queries
    public function newQuery()
    {
        return parent::newQuery()->orderBy('updated_at', 'DESC');
    }
}