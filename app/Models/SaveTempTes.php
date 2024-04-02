<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveTempTes extends Model
{
    use HasFactory;

    protected $table = 'temp_test';
    protected $fillable = [
        'user_id','test_id','json'
    ];

    public $timestamps = false;
}
