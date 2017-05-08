<?php

namespace Zhiyi\Plus\Interfaces;

use \Illuminate\Database\Eloquent\Model;

interface RepositoryInterface {

    /**
     * Get all.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function all(array $columns = ['*']);

    /**
     * Save a new model and return model instance.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function create(array $attributes = []): Model;

    /**
     * Update a record in the database.
     *
     * @param array $values
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(array $values): int;

    /**
     * Delete model By its primary key.
     *
     * @param int $id
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function delete(int $id): bool;

    /**
     * Find a model by its primary key.
     *
     * @param int $id
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function find(int $id, array $columns = ['*']);

    /**
     * Find a model.
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function findBy(string $field, $value, array $columns = ['*']);
}
