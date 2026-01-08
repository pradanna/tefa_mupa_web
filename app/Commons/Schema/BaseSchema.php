<?php

namespace App\Commons\Schema;
use Illuminate\Support\Facades\Validator;

class BaseSchema
{
    protected $body;
    protected $queryParams;

    public function hydrateSchemaBody($body)
    {
        $this->body = $body;
    }

    public function hydrateSchemaQueryParams($query)
    {
        $this->queryParams = $query;
    }

    protected function rules()
    {
        return [];
    }

    protected function messages()
    {
        return [];
    }

    public function validate()
    {
        return Validator::make($this->body, $this->rules(), $this->messages());
    }

    public function hydrateBody()
    {

    }

    public function hydrateQueryParams()
    {

    }

    /**
     * Get the body data
     *
     * @return array
     */
    public function getBody()
    {
        return $this->body ?? [];
    }
}
