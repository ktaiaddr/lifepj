<?php

namespace App\Models\HouseholdAccount;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EloquentTransaction extends Model
{
    use HasFactory;

    const CREATED_AT = 'created_at';
    const UPDATED_AT  = null;

//    public $timestamps = false; // disable all behavior
//    public $timestamps = true; // enable all behavior
    public $timestamps = [ "created_at" ]; // enable only to created_at
//    public $timestamps = [ "updated_at" ]; // enable only to updated_at
//    public $timestamps = [ "created_at", "updated_at" ]; // same as true, enable all

    protected $table = 'DAT_HOUSEHOLD_ACCOUNT_TRANSACTION';

    protected $primaryKey = 'transaction_id';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = ['transaction_id',
        'user_id',
        'date',
        'amount',
        'contents',
        'type',
        'created_at',
    ];
}
