<?php

namespace App\Schemas;

use App\Commons\Schema\BaseSchema;

class ContactSchema extends BaseSchema
{
    private $address;
    private $email;
    private $phone;
    private $weekday_hours;
    private $saturday_hours;
    private $facebook_url;
    private $instagram_url;
    private $tiktok_url;
    private $youtube_url;
    private $status;
    private $id_user;

    protected function rules(): array
    {
        return [
            'address'        => 'required|string',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:50',
            'weekday_hours'  => 'required|string|max:255',
            'saturday_hours' => 'nullable|string|max:255',
            'facebook_url'   => 'nullable|string|max:255',
            'instagram_url'  => 'nullable|string|max:255',
            'tiktok_url'     => 'nullable|string|max:255',
            'youtube_url'    => 'nullable|string|max:255',
            'status'         => 'required|in:publis,unpublis',
            'id_user'        => 'nullable|integer|exists:users,id',
        ];
    }

    protected function hydrateBody(): static
    {
        $this->setAddress($this->body['address'] ?? null)
            ->setEmail($this->body['email'] ?? null)
            ->setPhone($this->body['phone'] ?? null)
            ->setWeekdayHours($this->body['weekday_hours'] ?? null)
            ->setSaturdayHours($this->body['saturday_hours'] ?? null)
            ->setFacebookUrl($this->body['facebook_url'] ?? null)
            ->setInstagramUrl($this->body['instagram_url'] ?? null)
            ->setTiktokUrl($this->body['tiktok_url'] ?? null)
            ->setYoutubeUrl($this->body['youtube_url'] ?? null)
            ->setStatus($this->body['status'] ?? null)
            ->setIdUser($this->body['id_user'] ?? null);

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
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

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function getWeekdayHours()
    {
        return $this->weekday_hours;
    }

    public function setWeekdayHours($weekday_hours)
    {
        $this->weekday_hours = $weekday_hours;
        return $this;
    }

    public function getSaturdayHours()
    {
        return $this->saturday_hours;
    }

    public function setSaturdayHours($saturday_hours)
    {
        $this->saturday_hours = $saturday_hours;
        return $this;
    }

    public function getFacebookUrl()
    {
        return $this->facebook_url;
    }

    public function setFacebookUrl($facebook_url)
    {
        $this->facebook_url = $facebook_url;
        return $this;
    }

    public function getInstagramUrl()
    {
        return $this->instagram_url;
    }

    public function setInstagramUrl($instagram_url)
    {
        $this->instagram_url = $instagram_url;
        return $this;
    }

    public function getTiktokUrl()
    {
        return $this->tiktok_url;
    }

    public function setTiktokUrl($tiktok_url)
    {
        $this->tiktok_url = $tiktok_url;
        return $this;
    }

    public function getYoutubeUrl()
    {
        return $this->youtube_url;
    }

    public function setYoutubeUrl($youtube_url)
    {
        $this->youtube_url = $youtube_url;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
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
}


