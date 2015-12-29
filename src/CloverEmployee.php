<?php

namespace Wheniwork\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class CloverEmployee implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->response['id'];
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->response['name'];
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->response['email'];
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->response['role'];
    }

    /**
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->getRole() === 'ADMIN';
    }

    /**
     * @return boolean
     */
    public function isManager()
    {
        return $this->getRole() === 'MANAGER';
    }

    /**
     * @return boolean
     */
    public function isEmployee()
    {
        return $this->getRole() === 'EMPLOYEE';
    }
}
