<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KodeLevel extends Model
{
    //
    protected $table = 't_level';
    public $timestamps = false;
    public function Pegawai() {
        return $this->belongsTo('App\User','level', 'level_id');
    }
}
