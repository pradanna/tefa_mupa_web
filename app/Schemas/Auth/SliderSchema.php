<?php

namespace App\Schemas\Auth;

use App\Commons\Schema\BaseSchema;

class SliderSchema extends BaseSchema {
    private $title;
    private $file;
    private $path;

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }


    public function hydrateBody()
    {
        $title = $this->body['title'];
        $file = $this->body['file'];
        $path = $this->body['path'];
        $this->setTitle($title)
            ->setFile($path)
            ->setFile($file);
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
