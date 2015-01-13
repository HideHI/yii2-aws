<?php

namespace jambroo\aws\view\helper;

use Yii;

use jambroo\aws\view\exception\InvalidSchemeException;

/**
 * Common functionality for link generation.
 *
 * @author James Brooking <jambroo@gmail.com>
 * @since 1.0
 */
class AbstractLinkHelper
{
    /**
     * @var string
     */
    protected $scheme = 'https';

    /**
     * @var array
     */
    protected $supportedSchemes = array('http', 'https', null);

    /**
     * Set if HTTPS should be used for generating URLs
     *
     * @param bool $useSsl
     *
     * @return self
     * @deprecated
     */
    public function setUseSsl($useSsl)
    {
        $this->setScheme($useSsl ? 'https' : 'http');

        return $this;
    }

    /**
     * Get if HTTPS should be used for generating URLs
     *
     * @return bool
     * @deprecated
     */
    public function getUseSsl()
    {
        return $this->getScheme() === 'https';
    }

    /**
     * Set the scheme to use for generating URLs.  Supported schemes
     * are http, https and null (see {@link $supportedSchemes}).
     *
     * @param string $scheme
     * @throws InvalidSchemeException
     * @return self
     */
    public function setScheme($scheme)
    {
        if (!in_array($scheme, $this->supportedSchemes, true)) {
            $schemes = implode(', ', $this->supportedSchemes);

            throw new InvalidSchemeException('Schemes must be one of ' . $schemes);
        }

        $this->scheme = $scheme;

        return $this;
    }

    /**
     * Get the scheme to be used for generating URLs
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Return url with valid scheme or if no scheme provided prepend // to URL
     *
     * @param $url URL of AWS item
     * 
     * @return string
     */
    protected function cleanScheme($url)
    {
        foreach ($this->supportedSchemes as $supportedScheme) {
            if ($supportedScheme && (substr($url, 0, strlen($supportedScheme)) == $supportedScheme)) {
                return $url;
            }
        }

        return '//'.$url;
    }
}