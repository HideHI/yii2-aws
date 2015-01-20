<?php

use Aws\Common\Aws;
use Aws\S3\S3Client;
use Aws\Common\Client\UserAgentListener;
use jambroo\aws\factory\AwsFactory;
use jambroo\aws\factory\CloudFrontLinkViewHelperFactory;
use jambroo\aws\factory\DynamoDbSessionSaveHandlerFactory;
use jambroo\aws\factory\S3RenameUploadFactory;
use jambroo\aws\factory\S3LinkViewHelperFactory;
use Guzzle\Common\Event;
use Guzzle\Service\Client;

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

    public function testS3RenameUploadFactory()
    {
        $s3RenameUploadFactory = new S3RenameUploadFactory();
        $s3RenameUpload = $s3RenameUploadFactory->createService($this->awsSettings);
        
        $this->assertInstanceOf('jambroo\aws\filter\file\S3RenameUpload', $s3RenameUpload);
    }


    public function testS3LinkViewHelper()
    {
        $s3LinkViewHelperFactory = new S3LinkViewHelperFactory();
        $s3LinkViewHelper = $s3LinkViewHelperFactory->createService($this->awsSettings);
        
        $this->assertInstanceOf('jambroo\aws\view\helper\S3Link', $s3LinkViewHelper);
    }
}