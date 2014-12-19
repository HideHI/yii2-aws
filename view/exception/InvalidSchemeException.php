<?php

namespace jambroo\yii2-aws\view\exception;

use Aws\Common\Exception\AwsExceptionInterface;
use InvalidArgumentException;

/**
 * Exception thrown when an invalid CloudFront domain is passed
 */
class InvalidDomainNameException extends InvalidArgumentException implements AwsExceptionInterface
{
}