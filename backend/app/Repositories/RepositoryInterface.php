<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all records with optional relations
     *
     * @param  array  $relations  Relations to eager load
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(array $relations = []);

    /**
     * Find record by ID
     *
     * @param  int  $id  Record ID
     * @param  array  $relations  Relations to eager load
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id, array $relations = []);

    /**
     * Find record by specific field
     *
     * @param  string  $field  Field name
     * @param  mixed  $value  Field value
     * @param  array  $relations  Relations to eager load
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findBy(string $field, $value, array $relations = []);

    /**
     * Create new record
     *
     * @param  array  $data  Record data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Update record by ID
     *
     * @param  int  $id  Record ID
     * @param  array  $data  Update data
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function update(int $id, array $data);

    /**
     * Delete record by ID
     *
     * @param  int  $id  Record ID
     */
    public function delete(int $id): bool;

    /**
     * Find or create record
     *
     * @param  array  $attributes  Attributes to search by
     * @param  array  $values  Values to set if creating
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $attributes, array $values = []);
}
