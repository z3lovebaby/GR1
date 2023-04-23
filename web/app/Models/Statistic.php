<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'tbl_statistical';
    public $primaryKey = 'id_statistical';
}
