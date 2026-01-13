<?php

namespace App\Schemas;
use App\Commons\Schema\BaseSchema;

class CatalogSchema extends BaseSchema
{
    private $title;
    private $id_sub_category;
    private $id_category;
    private $image;
    private $path;
    private $desc;
    private $id_user;
    private $specification;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'id_sub_category' => 'required|integer',
            'id_category' => 'required|integer',
            'image' => 'required|string|max:255',
            'path' => 'required|string|max:255',
            'desc' => 'required|string',
            'specification' => 'nullable|string',
            'id_user' => 'required|integer',
        ];
    }

    protected function hydrateBody(): static
    {
        $this->setTitle($this->body['title'] ?? null)
            ->setIdSubCategory($this->body['id_sub_category'] ?? null)
            ->setIdCategory($this->body['id_category'] ?? null)
            ->setImage($this->body['image'] ?? null)
            ->setPath($this->body['path'] ?? null)
            ->setDesc($this->body['desc'] ?? null)
            ->setSpecification($this->body['specification'] ?? null)
            ->setIdUser($this->body['id_user'] ?? null);
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getIdSubCategory()
    {
        return $this->id_sub_category;
    }

    public function setIdSubCategory($id_sub_category)
    {
        $this->id_sub_category = $id_sub_category;
        return $this;
    }

    public function getIdCategory()
    {
        return $this->id_category;
    }

    public function setIdCategory($id_category)
    {
        $this->id_category = $id_category;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getDesc()
    {
        return $this->desc;
    }

    public function setDesc($desc)
    {
        $this->desc = $desc;
        return $this;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
        return $this;
    }

    public function getSpecification()
    {
        return $this->specification;
    }

    public function setSpecification($specification)
    {
        $this->specification = $specification;
        return $this;
    }
}
