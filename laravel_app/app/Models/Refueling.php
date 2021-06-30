<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refueling extends Model
{
    use HasFactory;

    protected $table = 'refuelings';

    protected $primaryKey = 'refueling_id';

}
