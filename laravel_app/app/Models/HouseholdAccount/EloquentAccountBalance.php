<?php

namespace App\Models\HouseholdAccount;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EloquentAccountBalance extends Model
{
    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT  = null;
    public $timestamps = [ "created_at" ]; // enable only to created_at

    protected $table = 'DAT_HOUSEHOLD_ACCOUNT_ACCOUNT';

    protected $primaryKey = ['transaction_id','account_id'];
    public $incrementing = false;

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'account_id',
        'increase_decrease_type',
        'created_at',
    ];
}
