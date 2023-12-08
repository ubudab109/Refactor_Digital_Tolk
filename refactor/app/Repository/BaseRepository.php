<?php

namespace DTApi\Repository;

use Validator;
use Illuminate\Database\Eloquent\Model;
use DTApi\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/*

Type Declarations:

Type declarations have been added to properties and methods for better code hinting and readability.
Consistent Naming:

Renamed _validate method to handleValidation for consistency and clarity.
Constructor Property Promotion:

Moved the throw statement outside the if block in the _validate method for consistency.
Removed Redundant Return Types:

Removed redundant return types from methods where the return type is implied by the usage.

The decision to make BaseRepository an abstract class is a design choice aimed at providing a base structure for repository classes while ensuring that instances of BaseRepository itself cannot be created.
By making BaseRepository abstract, I'm signaling that it should not be instantiated directly. Instead, it serves as a blueprint for other classes.
Abstract classes are often used to define a common interface and share code among several related classes.
In the BaseRepository abstract class, you've included some common functionality or methods that are expected to be shared by its subclasses.
Subclasses (like BookingRepository) extend the abstract class and provide concrete implementations for the abstract methods.
Abstract classes allow you to define a contract by declaring abstract methods that must be implemented by concrete subclasses.
This helps in enforcing a certain structure for classes that extend BaseRepository.
Subclasses of an abstract class can be used interchangeably through polymorphism, treating them as instances of the base abstract class.
 */
abstract class BaseRepository
{
    protected Model $model;
    protected array $validationRules = [];

    public function __construct(Model $model = null)
    {
        $this->model = $model;
    }

    public function validatorAttributeNames(): array
    {
        return [];
    }

    public function getModel()
    {
        return $this->model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function with($array)
    {
        return $this->model->with($array);
    }

    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function query()
    {
        return $this->model->query();
    }

    public function instance(array $attributes = [])
    {
        return new $this->model($attributes);
    }

    public function paginate($perPage = null)
    {
        return $this->model->paginate($perPage);
    }

    public function where($key, $where)
    {
        return $this->model->where($key, $where);
    }

    public function validator(array $data = [], $rules = null, array $messages = [], array $customAttributes = [])
    {
        if (is_null($rules)) {
            $rules = $this->validationRules;
        }

        return Validator::make($data, $rules, $messages, $customAttributes);
    }

    public function validate(array $data = [], $rules = null, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->validator($data, $rules, $messages, $customAttributes);
        return $this->handleValidation($validator);
    }

    public function create(array $data = []): Model
    {
        return $this->model->create($data);
    }

    public function update($id, array $data = []): Model
    {
        $instance = $this->findOrFail($id);
        $instance->update($data);
        return $instance;
    }

    public function delete($id): Model
    {
        $model = $this->findOrFail($id);
        $model->delete();
        return $model;
    }

    protected function handleValidation(\Illuminate\Validation\Validator $validator): bool
    {
        if (!empty($attributeNames = $this->validatorAttributeNames())) {
            $validator->setAttributeNames($attributeNames);
        }

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }
}