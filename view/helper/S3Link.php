<?php

namespace jambroo\aws\view\helper;

use Yii;

use Aws\Common\Aws;
use Aws\S3\S3Client;
use Aws\S3\BucketStyleListener;
use Guzzle\Common\Event;

use jambroo\aws\view\exception\InvalidDomainNameException;

/**
 * View helper that can render a link to a S3 object. It can also create signed URLs
 *
 * @author James Brooking <jambroo@gmail.com>
 * @since 1.0
 */
class S3Link extends AbstractLinkHelper
{
    /**
     * @var S3Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $defaultBucket = '';

    /**
     * @param S3Client $client
     */
    public function __construct(S3Client $client)
    {
        $this->client = $client;
    }

    /**
     * Set the default bucket to use if none is provided
     *
     * @param string $defaultBucket
     *
     * @return self
     */
    public function setDefaultBucket($defaultBucket)
    {
        $this->defaultBucket = (string) $defaultBucket;

        return $this;
    }

    /**
     * Get the default bucket to use if none is provided
     *
     * @return string
     */
    public function getDefaultBucket()
    {
        return $this->defaultBucket;
    }

    /**
     * Create a link to a S3 object from a bucket. If expiration is not empty, then it is used to create
     * a signed URL
     *
     * @param  string     $object The object name (full path)
     * @param  string     $bucket The bucket name
     * @param  string|int $expiration The Unix timestamp to expire at or a string that can be evaluated by strtotime
     * @throws InvalidDomainNameException
     * @return string
     */
    public function __invoke($object, $bucket = '', $expiration = '')
    {
        $bucket = trim($bucket ?: $this->getDefaultBucket(), '/');
        if (empty($bucket)) {
            throw new InvalidDomainNameException('An empty bucket name was given');
        }

        // Create a command representing the get request
        // Using a command will make sure the configured regional endpoint is used
        $command = $this->client->getCommand('GetObject', array(
            'Bucket' => $bucket,
            'Key'    => $object,
        ));

        // Instead of executing the command, retrieve the request and make sure the scheme is set to what was specified
        $request = $command->prepare()->setScheme($this->getScheme())->setPort(null);

        // Ensure that the correct bucket URL style (virtual or path) is used based on the bucket name
        // This addresses a bug in versions of the SDK less than or equal to 2.3.4
        // @codeCoverageIgnoreStart
        if (version_compare(Aws::VERSION, '2.4.0', '<') && strpos($request->getHost(), $bucket) === false) {
            $bucketStyleListener = new BucketStyleListener();
            $bucketStyleListener->onCommandBeforeSend(new Event(array('command' => $command)));
        }
        // @codeCoverageIgnoreEnd

        if ($expiration) {
            $url = $this->client->createPresignedUrl($request, $expiration);
        } else {
            $url = $request->getUrl();
        }

        if ((substr($url, 0, 8) != 'https://') &&
            (substr($url, 0, 7) != 'http://') &&
            (substr($url, 0, 2) !== '//')) {
            $url = '//'.$url;
        }

        return $url;
    }
}