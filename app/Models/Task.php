<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use App\QueryFilters\Task\OnlyTrashed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;

class Task extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'uuid',
        'parent_id',
        'title',
        'body',
        'state_id',
        'sort_order',
        'user_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['id', 'parent_id', 'user_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (Task $task): void {
            if (blank($task->sort_order)) {
                $task->setAttribute('sort_order', 1);
            }
            /** @var $lastSortOrder */
            $lastSortOrder = (int) static::query()->max('sort_order');

            $task->setAttribute('sort_order', ++$lastSortOrder);
        });
    }

    /*
     *******************************************************************************
     * Eloquent Relationships
     * @doc https://laravel.com/docs/8.x/eloquent-relationships
     *******************************************************************************
     */

    /**
     * Get all latest tasks that related to parent task
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subTasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id')->latest('id');
    }

    /**
     * Get associated state
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /*
     *******************************************************************************
     * Local scopes
     * @doc https://laravel.com/docs/8.x/eloquent#local-scopes
     *******************************************************************************
     */

    /**
     * Get the parent tasks
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnlyTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get specific sub task
     *
     * @param Builder $query
     * @param int $id
     * @return Builder
     */
    public function scopeBelongsToTaskId(Builder $query, int $id): Builder
    {
        return $query->where('parent_id', $id);
    }

    /**
     * Get result base on user id
     *
     * @param Builder $query
     * @param int $id
     * @return Builder
     */
    public function scopeBelongsToOwnerId(Builder $query, int $id): Builder
    {
        return $query->where('user_id', $id);
    }

    /**
     * Get all tasks with subTasks count and state relationship sort by id
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeGetAllTasks(Builder $query): Builder
    {
        return $query
            ->select([
                'uuid',
                'title',
                'body',
                'state_id',
                'sort_order',
                'created_at'
            ])
            ->withCount('subTasks')
            ->with('state')
            ->latest('id');
    }

    /**
     * Filter result
     *
     * @param Builder $query
     * @return mixed
     */
    public function scopeHasFiltered(Builder $query): mixed
    {
        return app(Pipeline::class)
            ->send($query)
            ->through([
                OnlyTrashed::class
            ])
            ->thenReturn();
    }

    /*
     *******************************************************************************
     * Helpers
     *******************************************************************************
     */

    /**
     * Task indicate that this is a subtask
     *
     * @return bool
     */
    public function isSubTask(): bool
    {
        return filled($this->parent_id);
    }
}
