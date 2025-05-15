<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Database table constants
     */
    public const TABLE = 'tags';
    public const ID = 'id';
    public const NAME = 'name';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';

    /**
     * Pivot table constants
     */
    public const PIVOT_TABLE = 'article_tag';
    public const ARTICLE_ID = 'article_id';
    public const TAG_ID = 'tag_id';

    /**
     * The table associated with the model.
     */
    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::NAME,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime',
    ];

    /**
     * The articles that belong to the tag.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, self::PIVOT_TABLE)
            ->withTimestamps();
    }
}