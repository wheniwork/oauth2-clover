<?php

namespace Wheniwork\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;

class Clover extends AbstractProvider
{
    /**
     * @var string
     */
    public $marketPrefix;

    public $method = 'get';

    public $authorizationHeader = 'Bearer';

    public function __construct($options = [])
    {
        if (!empty($options['marketPrefix'])) {
            // Ensure that the market domain prefix always starts with a dot
            if ($options['marketPrefix'][0] !== '.') {
                $options['marketPrefix'] = '.' . $options['marketPrefix'];
            }
        }
        parent::__construct($options);
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

    public function urlAuthorize()
    {
        return sprintf(
            'https://www%s.clover.com/oauth/authorize',
            $this->marketPrefix
        );
    }

    public function urlAccessToken()
    {
        return sprintf(
            'https://www%s.clover.com/oauth/token',
            $this->marketPrefix
        );
    }

    public function urlUserDetails(AccessToken $token)
    {
        return $this->getApiUrl('merchants/current/employees/current');
    }

    public function userDetails($response, AccessToken $token)
    {
        // Ensure the response is converted to an array, recursively
        $response = json_decode(json_encode($response), true);
        $user = new CloverEmployee($response);
        return $user;
    }

    /**
     * Helper method that can be used to fetch API responses.
     *
     * @param  string      $path
     * @param  AccessToken $token
     * @param  boolean     $as_array
     * @return array|object
     */
    public function getApiResponse($path, AccessToken $token, $as_array = true)
    {
        $url = $this->getApiUrl($path);

        $headers = $this->getHeaders($token);

        return json_decode($this->fetchProviderData($url, $headers), $as_array);
    }
}
