<?php

namespace jambroo\aws\factory;

use yii\base\Component;
use yii\base\InvalidConfigException;
use Yii;

use Aws\Common\Aws;
use Aws\Common\Client\UserAgentListener;
use Aws\Module;
use Guzzle\Common\Event;
use Guzzle\Service\Client;

use jambroo\aws\view\helper\CloudFrontLink;

/**
 * CloudFrontLinkViewHelperFactory represents a factory class for generating AWS CloudFront links.
 *
 * @author James Brooking <jambroo@gmail.com>
 * @since 1.0
 */
class CloudFrontLinkViewHelperFactory extends Component
{
    public $config;

    /**
     * Initializes the AWS Connection.
     *
     * @return AWS Instance
     */
    public function createService($serviceLocator)
    {
        $awsFactory     = new AwsFactory();
        $aws = $awsFactory->createService(Yii::$app->aws);

        $cloudFrontClient = $aws->get('CloudFront');

        return new CloudFrontLink($cloudFrontClient);
    }
}