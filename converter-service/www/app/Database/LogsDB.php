<?php

namespace App\Database;


/**
 * Class User for Illuminate (DB) queries
 */
class LogsDB extends AbstractModel
{

    protected $table = 'logsdb';
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'file',
        'tmp_name',
        'size',
        'start',
        'end',
        'status'
    ];

}
