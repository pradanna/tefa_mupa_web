<?php

namespace App\Schemas;

use App\Commons\Schema\BaseSchema;

class PromotionsSchema extends BaseSchema
{
    private $name;
    private $desc;
    private $image;
    private $code;
    private $expired;

    protected function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'desc'    => 'required|string|max:255',
            'image'   => 'required|string|max:255',
            'code'    => 'required|string|max:255',
            'expired' => 'required|date',
        ];
    }

    protected function hydrateBody(): static
    {
        $this->setName($this->body['name'] ?? null)
            ->setDesc($this->body['desc'] ?? null)
            ->setImage($this->body['image'] ?? null)
            ->setCode($this->body['code'] ?? null)
            ->setExpired($this->body['expired'] ?? null);
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

    public function getDesc()
    {
        return $this->desc;
    }
    public function setDesc($desc)
    {
        $this->desc = $desc;
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

    public function getCode()
    {
        return $this->code;
    }
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getExpired()
    {
        return $this->expired;
    }
    public function setExpired($expired)
    {
        $this->expired = $expired;
        return $this;
    }
}
