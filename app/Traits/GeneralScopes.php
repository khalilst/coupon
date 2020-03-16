<?php

namespace App\Traits;

use App\Enums\EOperator;

trait GeneralScopes
{
    /**
     * Scope a query to only include multiple filtered records.
     * Data Format: $data['field_name']['operator']
     * Available Formats: min, max, like, eq.
     * For more information about operators see the scopeFilterBy method.
     *
     * @param  array $data
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $data)
    {
        foreach ($data as $field => $fieldData) {
            foreach ($fieldData as $operator => $value) {
                $query->filterBy($field, $operator, $value);
            }
        }
        return $query;
    }

    /**
     * This method applies filter with respect to given params.
     *
     * Available operators for the following format:
     *     min  ~~> Greater then or Equal <=> $field >= $value
     *     max  ~~> Less then or Equal <=> $field <= $value
     *     like ~~> Like operator <=> $field LIKE $value
     *     eq   ~~> Exact Search <=> $field == $value
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterBy($query, $field, $operator, $value)
    {
        switch ($operator) {
            case EOperator::MIN:
                $query->where($field, '>=', $value);
                break;

            case EOperator::MAX:
                $query->where($field, '<=', $value);
                break;

            case EOperator::LIKE:
                $query->where($field, 'like', $value);
                break;

            case EOperator::EQUAL:
                $query->where($field, $value);
                break;
        }

        return $query;
    }

    /**
     * Scope a query to only apply ordering data.
     * Ordering Data Format: [field] = Direction
     * Direction: ASC, DESC
     *
     * @param  array $data
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrder($query, $data)
    {
        foreach ($data as $field => $direction) {
            $query->orderBy($field, $direction);
        }

        return $query;
    }
}
