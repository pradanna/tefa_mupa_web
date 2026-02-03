<?php

namespace App\Schemas;

use App\Commons\Schema\BaseSchema;

class SliderSchema extends BaseSchema {
    private $title;
    private $subtitle;
    private $file;
    private $path;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'file' => 'required|string',
            'path' => 'required|string',
        ];
    }


    protected function hydrateBody(): static
    {
        $this->setTitle($this->body['title'] ?? null)
            ->setSubtitle($this->body['subtitle'] ?? null)
            ->setFile($this->body['file'] ?? null)
            ->setPath($this->body['path'] ?? null);
        return $this;
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

    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
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
