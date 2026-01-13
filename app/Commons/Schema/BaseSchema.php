<?php

namespace App\Commons\Schema;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseSchema
{
    protected array $body = [];
    protected array $queryParams = [];

    public function hydrateSchemaBody(array $body): static
    {
        $this->body = $body;
        return $this; // 🔥 KUNCI
    }

    public function hydrateSchemaQueryParams(array $query): static
    {
        $this->queryParams = $query;
        return $this;
    }

    abstract protected function rules(): array;

    protected function messages(): array
    {
        return [];
    }

    public function validate(): static
    {
        $validator = Validator::make(
            $this->body,
            $this->rules(),
            $this->messages()
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);

        }

        return $this; // 🔥 KUNCI
    }

    abstract protected function hydrateBody(): static;

    protected function hydrateQueryParams(): static
    {
        return $this;
    }

    public function hydrate(): static
    {
        $this->hydrateBody();
        $this->hydrateQueryParams();
        return $this;
    }

    public function getBody(): array
    {
        return $this->body;
    }
}
