<?php

namespace App\Schemas\auth;

use App\Commons\Schema\BaseSchema;
use Illuminate\Validation\Rules\Password;

class LoginSchema extends BaseSchema {
    private $email;
    private $password;

    protected function rules()
    {
        return [
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->numbers()
                    ->mixedCase()
                    ->symbols(),
            ],
        ];
    }
    public function hydrateBody()
    {
        $email = $this->body['email'];
        $password = $this->body['password'];
        $this->setEmail($email)
            ->setPassword($password);
    }


    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}

