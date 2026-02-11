<?php

namespace App\Schemas;

use App\Commons\Schema\BaseSchema;

class VisionMissionSchema extends BaseSchema
{
    private $type;
    private $content;

    protected function rules(): array
    {
        return [
            'type' => 'required|in:vision,mission',
            'content' => 'nullable|string',
        ];
    }

    protected function hydrateBody(): static
    {
        $this->setType($this->body['type'] ?? null)
            ->setContent($this->body['content'] ?? null);
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

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
}


