<?php

namespace App\Schemas;

use App\Commons\Schema\BaseSchema;
use Illuminate\Validation\Rule;

class NewsSchema extends BaseSchema
{
    private $title;
    private $slug;
    private $id_category;
    private $content;
    private $image;
    private $path;   // Added path property
    private $date;
    private $status;
    private $id_user;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('news', 'slug')->ignore($this->body['id'] ?? null),
            ],
            'id_category' => 'required|integer|exists:categories,id',
            'content' => 'required|string',
            'image' => 'nullable|string|max:255',
            'path'  => 'nullable|string',
            'date' => 'required|date',
            'status' => 'required|in:publis,unpublis',
            'id_user' => 'nullable|integer|exists:users,id',
        ];
    }

    public function hydrateBody()
    {
        $this->setTitle($this->body['title'] ?? null)
            ->setSlug($this->body['slug'] ?? null)
            ->setIdCategory($this->body['id_category'] ?? null)
            ->setContent($this->body['content'] ?? null)
            ->setImage($this->body['image'] ?? null)
            ->setPath($this->body['path'] ?? null) // Hydrate path
            ->setDate($this->body['date'] ?? null)
            ->setStatus($this->body['status'] ?? null)
            ->setIdUser($this->body['id_user'] ?? null);
    }

    // Title
    public function getTitle()
    {
        return $this->title;
    }
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    // Slug
    public function getSlug()
    {
        return $this->slug;
    }
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    // Id Category
    public function getIdCategory()
    {
        return $this->id_category;
    }
    public function setIdCategory($id_category)
    {
        $this->id_category = $id_category;
        return $this;
    }

    // Content
    public function getContent()
    {
        return $this->content;
    }
    public function setContent($content)
    {
        $this->content = $content;
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

    // Path
    public function getPath()
    {
        return $this->path;
    }
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    // Date
    public function getDate()
    {
        return $this->date;
    }
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    // Status
    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    // Id User
    public function getIdUser()
    {
        return $this->id_user;
    }
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
        return $this;
    }
}
