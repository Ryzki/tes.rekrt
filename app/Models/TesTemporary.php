<?php

namespace App\Models;

use App\Models\Test;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TesTemporary extends Model
{
    use HasFactory;

    protected $table = 'test_temporary';

    protected $fillable = [
        'id_user',
        'test_id',
        'json',
        'packet_id',
        'part',
        'result_temp'
    ];

    public function tes(){
        return $this->belongsTo(Test::class, 'test_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public $timestamps = false;
}
