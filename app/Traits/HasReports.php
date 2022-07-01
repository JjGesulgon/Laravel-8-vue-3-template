<?php

/*
|--------------------------------------------------------------------------
| Auto generation of statistics for laravel models
|--------------------------------------------------------------------------
|
| Here is where all the logic happens for generating statistics for a model.
|
*/

namespace App\Traits;

use Illuminate\Support\Carbon;

trait HasReports
{
    /**
     * Attributes that will auto generated for statistics.
     *
     * @var array
     */
    protected $methods = [
        'total_count_yesterday' => [
            'created_at' => 'yesterday'
        ],
        'total_count_today' => [
            'created_at' => 'today'
        ].
        'total_count_last_week',
        'total_count_this_week',
        'total_count_last_month',
        'total_count_this_month'
    ];

    protected function __call($method)
    {
        if (! in_array($method, array_keys($this->methods))) {
            throw new BadMethodCallException();
        }

        // return call_user_func_array($this->methods[$method]);
    }
}
