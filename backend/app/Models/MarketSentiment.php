<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketSentiment extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * Table name
     */
    public const TABLE = 'market_sentiments';

    /**
     * Column names
     */
    public const COLUMN_TYPE = 'type';
    public const COLUMN_CONTEXT = 'context';
    public const COLUMN_EXPIRED_AT = 'expired_at';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
    public const COLUMN_DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_TYPE,
        self::COLUMN_CONTEXT,
        self::COLUMN_EXPIRED_AT,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        self::COLUMN_EXPIRED_AT => 'datetime',
        self::COLUMN_CREATED_AT => 'datetime',
        self::COLUMN_UPDATED_AT => 'datetime',
        self::COLUMN_DELETED_AT => 'datetime',
        self::COLUMN_CONTEXT => 'array',
    ];
}