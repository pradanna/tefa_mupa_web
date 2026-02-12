<?php

namespace App\Schemas;

use App\Commons\Schema\BaseSchema;

class LicenseSchema extends BaseSchema
{
    private $name;
    private $code;
    private $type;
    private $file;

    public function rules(): array
    {
        return [
            'name' => 'required',
            'code' => 'required',
            'type' => 'required',
            'file' => 'nullable',
        ];
    }

    protected function hydrateBody(): static
    {
        $this->setName($this->body['name'] ?? null)
            ->setCode($this->body['code'] ?? null)
            ->setType($this->body['type'] ?? null)
            ->setFile($this->body['file'] ?? null);

        return $this;
    }


    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get the value of file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }
}
