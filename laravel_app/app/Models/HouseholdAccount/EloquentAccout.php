<?php

namespace App\Models\HouseholdAccount;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EloquentAccout extends Model
{
    use HasFactory;

//    const CREATED_AT = 'created_at';
//    const UPDATED_AT  = 'updated_at';
//    public $timestamps = [ "created_at", 'updated_at' ];

    protected $table = 'MST_ACCOUNT';

    protected $primaryKey = 'account_id';

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'user_id',
        'type',
        'name',
        'created_at',
        'updated_at',
    ];
}
