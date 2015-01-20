<?php

namespace jambroo\aws\factory;

use yii\base\Component;
use jambroo\aws\view\helper\CloudFrontLink;

/**
 * CloudFrontLinkViewHelperFactory represents a factory class for generating AWS CloudFront links.
 *
 * @author James Brooking <jambroo@gmail.com>
 * @since 1.0
 */
class CloudFrontLinkViewHelperFactory extends Component
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

        $cloudFrontClient = $aws->get('CloudFront');

        return new CloudFrontLink($cloudFrontClient);
    }
}