<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TokenReport extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Constants for table fields
     */
    public const FIELD_ID = 'id';
    public const FIELD_TOKEN_ID = 'token_id';
    public const FIELD_TYPE = 'type';
    public const FIELD_CONTEXT = 'context';
    public const FIELD_EXPIRED_AT = 'expired_at';
    public const FIELD_CREATED_AT = 'created_at';
    public const FIELD_UPDATED_AT = 'updated_at';
    public const FIELD_DELETED_AT = 'deleted_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        self::FIELD_TOKEN_ID,
        self::FIELD_TYPE,
        self::FIELD_CONTEXT,
        self::FIELD_EXPIRED_AT,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        self::FIELD_EXPIRED_AT => 'datetime',
        self::FIELD_CREATED_AT => 'datetime',
        self::FIELD_UPDATED_AT => 'datetime',
        self::FIELD_DELETED_AT => 'datetime',
        self::FIELD_CONTEXT => 'array',
    ];

    /**
     * Get the token that owns the report.
     */
    public function token(): BelongsTo
    {
        return $this->belongsTo(BlockchainToken::class);
    }
}
