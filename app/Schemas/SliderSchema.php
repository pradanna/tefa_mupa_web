<?php

namespace App\Schemas;

use App\Commons\Schema\BaseSchema;

class SliderSchema extends BaseSchema {
    private $title;
    private $file;
    private $path;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'file' => 'required|string',
            'path' => 'required|string',
        ];
    }


    public function hydrateBody()
    {
        $this->setTitle($this->body['title'] ?? null)
            ->setFile($this->body['file'] ?? null)
            ->setPath($this->body['path'] ?? null);
    }

    public function getTitle()
    {
        return $this->title;
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

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }
}
