<?php

use Aws\Common\Aws;
use Aws\S3\S3Client;
use Aws\Common\Client\UserAgentListener;
use jambroo\aws\factory\AwsFactory;
use jambroo\aws\factory\CloudFrontLinkViewHelperFactory;
use jambroo\aws\factory\DynamoDbSessionSaveHandlerFactory;

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
    protected $awsSettings;
    
    public function setUp()
    {
        $this->awsSettings = (object) [
            'class' => 'jambroo\aws\factory\AWSFactory',
            'config' => [
                'key'    => 'somekey',
                'secret' => 'somesecret',
                'region'  => 'someregion'
            ]
        ];
    }

    public function testCanFetchAwsFromServiceManager()
    {
        $awsFactory     = new AwsFactory();
        $aws = $awsFactory->createService($this->awsSettings);

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
        $cloudFrontLinkViewHelperFactory = new CloudFrontLinkViewHelperFactory();
        $cloudFrontLinkViewHelper = $cloudFrontLinkViewHelperFactory->createService($this->awsSettings);
        
        $this->assertInstanceOf('jambroo\aws\view\helper\CloudFrontLink', $cloudFrontLinkViewHelper);
    }

    /**
     * @expectedException \Exception
     */
    public function testDynamoDbSessionSaveHandlerFactoryNoSettings()
    {
        $dynamoDbSessionSaveHandlerFactory = new DynamoDbSessionSaveHandlerFactory();
        $dynamoDbSessionSaveHandler = $dynamoDbSessionSaveHandlerFactory->createService($this->awsSettings);
    }

    public function testDynamoDbSessionSaveHandlerFactory()
    {
        $awsSettings = (object) [
            'class' => 'jambroo\aws\factory\AWSFactory',
            'config' => [
                'key'    => 'somekey',
                'secret' => 'somesecret',
                'region'  => 'someregion',
                'session' => [
                    'save_handler' => [
                        'dynamodb' => [
                            'dynamodb_client' => null
                        ]
                    ]
                ]
            ]
        ]; 

        $dynamoDbSessionSaveHandlerFactory = new DynamoDbSessionSaveHandlerFactory();
        $dynamoDbSessionSaveHandler = $dynamoDbSessionSaveHandlerFactory->createService($awsSettings);

        $this->assertInstanceOf('jambroo\aws\session\saveHandler\DynamoDb', $dynamoDbSessionSaveHandler);
    }
}