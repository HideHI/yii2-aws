<?php

namespace jambroo\aws\factory;

use yii\base\Component;
use yii\base\InvalidConfigException;
use Yii;

use Aws\DynamoDb\Session\SessionHandler;
use jambroo\aws\session\saveHandler\DynamoDb as DynamoDbSaveHandler;

/**
 * Factory used to instantiate a DynamoDB-backed session save handler
 */
class DynamoDbSessionSaveHandlerFactory extends Component
{
    /**
     * {@inheritDoc}
     * @return DynamoDbSaveHandler
     * @throws \Exception if "dynamodb" configuration is not set up correctly
     */
    public function createService($serviceLocator)
    {
        $awsFactory     = new AwsFactory();
        $aws = $awsFactory->createService($serviceLocator);

        //$cloudFrontClient = $aws->get('CloudFront');
        $config = $serviceLocator->config;

        if (!isset($config['session']['save_handler']['dynamodb'])) {
            throw new \Exception(
                'YII2 AWS PHP SDK configuration is missing a "dynamodb" key.'
            );
        }

        $saveHandlerConfig = $config['session']['save_handler']['dynamodb'];

        if (!isset($saveHandlerConfig['dynamodb_client'])) {
            $dynamoDbClient = $aws->get('DynamoDb');

            $saveHandlerConfig['dynamodb_client'] = $dynamoDbClient;
        }

        $sessionHandler = SessionHandler::factory($saveHandlerConfig);

        return new DynamoDbSaveHandler($sessionHandler);
    }
}