<?php

namespace App\Domain\Services;

use Illuminate\Database\Eloquent\Model;

abstract class APivotDomainService
{

    public function __construct(protected string $relation)
    {
    }

    public function sync(Model $model, array $data)
    {
        $relation = strtolower($this->relation);
        return $model->{$relation}()->sync($data);
    }
}
