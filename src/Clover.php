<?php

namespace Wheniwork\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Clover extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * @var string
     */
    protected $marketPrefix;

    public function __construct(array $options = [], array $collaborators = [])
    {
        if (!empty($options['marketPrefix'])) {
            // Ensure that the market domain prefix always starts with a dot
            if ($options['marketPrefix'][0] !== '.') {
                $options['marketPrefix'] = '.' . $options['marketPrefix'];
            }
        }

        parent::__construct($options, $collaborators);
    }

    /**
     * Get a Clover API URL, depending on path.
     *
     * @param  string $path
     * @return string
     */
    protected function getApiUrl($path)
    {
        return sprintf(
            'https://api%s.clover.com/v3/%s',
            $this->marketPrefix,
            $path
        );
    }

    public function getBaseAuthorizationUrl()
    {
        return sprintf(
            'https://www%s.clover.com/oauth/authorize',
            $this->marketPrefix
        );
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return sprintf(
            'https://www%s.clover.com/oauth/token',
            $this->marketPrefix
        );
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->getApiUrl('merchants/current/employees/current');
    }

    protected function getAccessTokenMethod()
    {
        return static::METHOD_GET;
    }

    protected function getDefaultScopes()
    {
        return [];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        // Clover does not seem to expose useful error information :(
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new CloverEmployee($response);
    }
}
