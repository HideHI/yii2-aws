<?php

use Aws\Common\Aws;
use Aws\S3\S3Client;
use Aws\Common\Client\UserAgentListener;
use jambroo\aws\factory\AwsFactory;
use jambroo\aws\factory\CloudFrontLinkViewHelperFactory;

use Aws\Tests\BaseModuleTest;
use Guzzle\Common\Event;
use Guzzle\Service\Client;

use yii\di\ServiceLocator;
use yii\caching\FileCache;

/**
 * AWS Module test cases
 */
class AwsFactoryTest extends \Codeception\TestCase\Test
{
    public function testCanFetchAwsFromServiceManager()
    {
        $awsFactory     = new AwsFactory();
        $aws = $awsFactory->createService(Yii::$app->aws);

        $this->assertInstanceOf('Guzzle\Service\Builder\ServiceBuilderInterface', $aws);
        $this->assertTrue($aws->getEventDispatcher()->hasListeners('service_builder.create_client'));
    }

    public function testCanAddYii2ToUserAgent()
    {
        $factory = new AwsFactory();
        $client  = S3Client::factory();
        $event   = new Event(array('client' => $client));

        $factory->onCreateClient($event);
        $clientParams = $client->getConfig()->get(Client::COMMAND_PARAMS);

        $this->assertArrayHasKey(UserAgentListener::OPTION, $clientParams);
        $this->assertRegExp('/YII2\/.+YII2MOD\/.+/', $clientParams[UserAgentListener::OPTION]);
    }

    public function testCloudFrontLinkViewHelperFactory()
    {
        $cloudFrontLinkViewHelperFactory     = new CloudFrontLinkViewHelperFactory();
        $cloudFrontLinkViewHelper = $cloudFrontLinkViewHelperFactory->createService(Yii::$app->aws);
        
        $this->assertInstanceOf('jambroo\aws\view\helper\CloudFrontLink', $cloudFrontLinkViewHelper);
    }
}