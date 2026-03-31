<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Base Repository Class
 *
 * Provides common CRUD operations for all repositories.
 * All specific repositories should extend this class.
 */
abstract class BaseRepository implements RepositoryInterface
{
    /**
     * Eloquent model instance
     */
    protected Model $model;

    /**
     * Constructor
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records with optional relations
     *
     * @param  array  $relations  Relations to eager load
     */
    public function all(array $relations = []): Collection
    {
        return $this->model->with($relations)->get();
    }

    /**
     * Find record by ID
     *
     * @param  int  $id  Record ID
     * @param  array  $relations  Relations to eager load
     */
    public function find(int $id, array $relations = []): ?Model
    {
        return $this->model->with($relations)->find($id);
    }

    /**
     * Find record by specific field
     *
     * @param  string  $field  Field name
     * @param  mixed  $value  Field value
     * @param  array  $relations  Relations to eager load
     */
    public function findBy(string $field, $value, array $relations = []): ?Model
    {
        return $this->model->with($relations)->where($field, $value)->first();
    }

    /**
     * Create new record
     *
     * @param  array  $data  Record data
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update record by ID
     *
     * @param  int  $id  Record ID
     * @param  array  $data  Update data
     */
    public function update(int $id, array $data): ?Model
    {
        $record = $this->find($id);

        if ($record) {
            $record->update($data);

            return $record->fresh();
        }

        return null;
    }

    /**
     * Delete record by ID
     *
     * @param  int  $id  Record ID
     */
    public function delete(int $id): bool
    {
        $record = $this->find($id);

        if ($record) {
            return $record->delete();
        }

        return false;
    }

    /**
     * Find or create record
     *
     * @param  array  $attributes  Attributes to search by
     * @param  array  $values  Values to set if creating
     */
    public function firstOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    /**
     * Get query builder instance
     */
    public function query(): Builder
    {
        return $this->model->query();
    }

    /**
     * Check if record exists
     *
     * @param  int  $id  Record ID
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Count all records
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Get records with pagination
     *
     * @param  int  $perPage  Items per page
     * @param  array  $relations  Relations to eager load
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $relations = [])
    {
        return $this->model->with($relations)->paginate($perPage);
    }
}
