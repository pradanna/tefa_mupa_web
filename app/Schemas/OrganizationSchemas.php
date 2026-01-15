<?php

namespace App\Schemas;

use App\Commons\Schema\BaseSchema;
use App\Models\OrganizationStructure;

class OrganizationSchemas extends BaseSchema
{
    private $name;
    private $position;
    private $path;
    private $image;
    private $instagram;
    private $linkedin;
    private $email;
    private $order;

    protected function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'position'   => 'required|string|max:255',
            'path'       => 'required|string|max:255',
            'image'      => 'required|string|max:255',
            'instagram'  => 'nullable|string|max:255',
            'linkedin'   => 'nullable|string|max:255',
            'email'      => 'nullable|email|max:255',
            'order'      => 'required|integer',
        ];
    }

    protected function hydrateBody(): static
    {
        $this->setName($this->body['name'] ?? null)
            ->setPosition($this->body['position'] ?? null)
            ->setPath($this->body['path'] ?? null)
            ->setImage($this->body['image'] ?? null)
            ->setInstagram($this->body['instagram'] ?? null)
            ->setLinkedin($this->body['linkedin'] ?? null)
            ->setEmail($this->body['email'] ?? null)
            ->setOrder($this->body['order'] ?? null);
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

    public function getPosition()
    {
        return $this->position;
    }
    public function setPosition($position)
    {
        $this->position = $position;
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

    public function getImage()
    {
        return $this->image;
    }
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function getInstagram()
    {
        return $this->instagram;
    }
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
        return $this;
    }

    public function getLinkedin()
    {
        return $this->linkedin;
    }
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }
}
