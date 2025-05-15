<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlockchainToken extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tokens';

    /**
     * Constants for table fields
     */
    public const FIELD_ID = 'id';
    public const FIELD_NAME = 'name';
    public const FIELD_SYMBOL = 'symbol';
    public const FIELD_CONTRACT_ADDRESS = 'contract_address';
    public const FIELD_CREATED_AT = 'created_at';
    public const FIELD_UPDATED_AT = 'updated_at';
    public const FIELD_DELETED_AT = 'deleted_at';
    public const FIELD_NETWORK = 'network';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        self::FIELD_NAME,
        self::FIELD_SYMBOL,
        self::FIELD_CONTRACT_ADDRESS,
        self::FIELD_NETWORK,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        self::FIELD_DELETED_AT => 'datetime',
    ];
}
