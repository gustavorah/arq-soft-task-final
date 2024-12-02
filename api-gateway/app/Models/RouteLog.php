<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteLog extends Model
{
    protected $table = "route_logs";

    protected $fillable = [
        "route",
        "method",
        "ip_address",
        "user_agent"
    ];
}
