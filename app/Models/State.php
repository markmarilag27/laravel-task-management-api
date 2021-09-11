<?php

namespace App\Models;

use App\QueryFilters\State\Name;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;

class State extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /*
     *******************************************************************************
     * Local scopes
     * @doc https://laravel.com/docs/8.x/eloquent#local-scopes
     *******************************************************************************
     */

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
                Name::class
            ])
            ->thenReturn();
    }
}
