<?php

namespace Wheniwork\OAuth2\Client\Provider;

class CloverEmployee 
{
    public $uid;
    public $name;
    public $email;
    public $inviteSent;
    public $claimedTime;
    public $role;

    public function __construct(array $attributes)
    {
        if (!empty($attributes['id'])) {
            $this->uid = $attributes['id'];
        }

        $attributes = array_intersect_key($attributes, $this->toArray());
        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }
    }

    public function isAdmin()
    {
        return $this->role === 'ADMIN';
    }

    public function isManager()
    {
        return $this->role === 'MANAGER';
    }

    public function isEmployee()
    {
        return $this->role === 'EMPLOYEE';
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}
