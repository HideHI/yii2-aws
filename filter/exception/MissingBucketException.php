<?php

namespace jambroo\aws\filter\exception;

use Aws\Common\Exception\InvalidArgumentException;

/**
 * Exception thrown when no bucket is passed
 */
class MissingBucketException extends InvalidArgumentException
{
}