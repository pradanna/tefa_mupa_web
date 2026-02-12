<?php

namespace App\Schemas;

use App\Commons\Schema\BaseSchema;
use Illuminate\Validation\Rule;

class CategorySchema extends BaseSchema
{
    private $type;
    private $name;
    private $slug;
    private $icon;
    private $description;

    protected function rules(): array
    {
        return [
            'type' => 'required|string|in:catalog,content,sub_catalog',
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->body['id'] ?? null),
            ],
            'icon' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }

    protected function hydrateBody(): static
    {
        $this->setType($this->body['type'] ?? null)
            ->setName($this->body['name'] ?? null)
            ->setSlug($this->body['slug'] ?? null)
            ->setIcon($this->body['icon'] ?? null)
            ->setDescription($this->body['description'] ?? null);
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}
