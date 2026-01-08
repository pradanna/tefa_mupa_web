<?php

namespace App\Schemas;
use App\Commons\Schema\BaseSchema;

class CategorySchema extends BaseSchema
{
    private $type;
    private $name;
    private $slug;
    private $icon;
    private $description;

    public function rules()
    {
        return [

            'type' => 'required|string|in:catalog,content',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }

    public function hydrateBody()
    {
        $this->setType($this->body['type'] ?? null)
            ->setName($this->body['name'] ?? null)
            ->setSlug($this->body['slug'] ?? null)
            ->setIcon($this->body['icon'] ?? null)
            ->setDescription($this->body['description'] ?? null);
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
