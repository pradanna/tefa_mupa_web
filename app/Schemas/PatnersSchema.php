<?php

namespace App\Schemas;

use App\Commons\Schema\BaseSchema;

class PatnersSchema extends BaseSchema
{
    private $name;
    private $image;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
        ];
    }

    protected function hydrateBody(): static
    {
        $this->setName($this->body['name'] ?? null)
            ->setImage($this->body['image'] ?? null);
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

    // Image
    public function getImage()
    {
        return $this->image;
    }
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }
}
