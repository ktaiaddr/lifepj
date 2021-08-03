<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Refueling
 * @property int $refueling_id
 * @property int $user_id
 * @property string $date
 * @property float $refueling_amount
 * @property float $refueling_distance
 * @property string $gas_station
 * @property string $memo
 * @property string $created_at
 * @property string $updated_at
 * @package App\Models
 */
class Refueling extends Model
{
    use HasFactory;

    protected $table = 'refuelings';

    protected $primaryKey = 'refueling_id';

}
