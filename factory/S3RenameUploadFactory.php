<?php

namespace jambroo\aws\factory;

use yii\base\Component;
use jambroo\aws\filter\file\S3RenameUpload;

/**
 * S3RenameUploadFactory represents a factory class for generating AWS S3RenameUpload helper class.
 *
 * @author James Brooking <jambroo@gmail.com>
 * @since 1.0
 */
class S3RenameUploadFactory extends Component
{
    /**
     * Initializes the AWS Connection.
     *
     * @return AWS Instance
     */
    public function createService($serviceLocator)
    {
        $awsFactory     = new AwsFactory();
        $aws = $awsFactory->createService($serviceLocator);

        $s3Client = $aws->get('S3');

        return new S3RenameUpload($s3Client);
    }
}