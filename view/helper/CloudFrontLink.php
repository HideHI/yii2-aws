<?php

namespace jambroo\aws\view\helper;

use Yii;

use Aws\CloudFront\CloudFrontClient;

use jambroo\aws\view\exception\InvalidDomainNameException;
use jambroo\aws\view\exception\InvalidSchemeException;

/**
 * View helper that can render a link to a CloudFront object. It can also create signed URLs
 * using a canned policy
 *
 * @author James Brooking <jambroo@gmail.com>
 * @since 1.0
 */
class CloudFrontLink extends AbstractLinkHelper
{
    /**
     * @var string The hostname for CloudFront domains
     */
    protected $hostname = '.cloudfront.net';
 
    /**
     * @var CloudFrontClient An instance of CloudFrontClient to be used by the helper
     */
    protected $client;
 
    /**
     * @var bool Whether or not to use SSl
     */
    protected $useSsl = true;
 
    /**
     * @var string The default CloudFront domain to use
     */
    protected $defaultDomain = '';
 
    /**
     * @param CloudFrontClient $client
     */
    public function __construct(CloudFrontClient $client)
    {
        $this->client = $client;
    }
 
    /**
     * Set the CloudFront hostname to use if you are using a custom hostname
     *
     * @param string $hostname
     *
     * @return self
     */
    public function setHostname($hostname)
    {
        $this->hostname = '.' . ltrim($hostname, '.');
        return $this;
    }
 
    /**
     * Get the CloudFront hostname being used
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }
 
    /**
     * Set the CloudFront domain to use if none is provided
     *
     * @param string $defaultDomain
     *
     * @return self
     */
    public function setDefaultDomain($defaultDomain)
    {
        $this->defaultDomain = (string) $defaultDomain;
        return $this;
    }
 
    /**
     * Get the CloudFront domain to use if none is provided
     *
     * @return string
     */
    public function getDefaultDomain()
    {
        return $this->defaultDomain;
    }
 
    /**
     * Create a link to a CloudFront object
     *
     * @param  string     $object
     * @param  string     $domain
     * @param  string|int $expiration
     *
     * @return string
     * @throws InvalidDomainNameException
     */
    public function __invoke($object, $domain = '', $expiration = '')
    {
        if (empty($domain)) {
            $domain = $this->getDefaultDomain();
        }
        // If $domain is still empty, we throw an exception as it makes no sense
        if (empty($domain)) {
            throw new InvalidDomainNameException('An empty CloudFront domain name was given');
        }
        $url = sprintf(
            '%s//%s%s/%s',
            ($this->scheme === null) ? null : $this->scheme . ':',
            // Remove hostname if provided as we include it already
            str_replace($this->hostname, '', rtrim($domain, '/')),
            $this->hostname,
            ltrim($object, '/')
        );
        if (empty($expiration)) {
            return $url;
        }
        if ($this->scheme === null) {
            throw new InvalidSchemeException('Protocol relative URLs cannot be signed.');
        }
        return $this->cleanScheme($this->client->getSignedUrl(array(
            'url'     => $url,
            'expires' => $expiration
        )));
    }
}