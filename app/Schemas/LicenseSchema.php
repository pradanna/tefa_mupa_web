<?php

namespace App\Schemas;

use App\Commons\Schema\BaseSchema;
use Illuminate\Validation\Rule;

class LicenseSchema extends BaseSchema
{
    private $name;
    private $code;
    private $type;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('licenses', 'code')->ignore($this->body['id'] ?? null),
            ],
            'type' => 'required|string|max:255',
        ];
    }

    protected function hydrateBody(): static
    {
        $this->setName($this->body['name'] ?? null)
            ->setCode($this->body['code'] ?? null)
            ->setType($this->body['type'] ?? null);
        return $this;
    }

    // Name
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    // Code
    public function getCode()
    {
        return $this->code;
    }
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    // Type
    public function getType()
    {
        return $this->type;
    }
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}
