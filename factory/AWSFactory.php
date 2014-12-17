<?php

namespace jambroo\aws;

use yii\base\Component;
use yii\base\InvalidConfigException;
use Yii;

use Aws\Common\Aws;
use Aws\Common\Client\UserAgentListener;
use Aws\Module;
use Guzzle\Common\Event;
use Guzzle\Service\Client;

/**
 * AWSFactory represents a factory class for generating AWS connections.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class AWSFactory extends Component
{
    
    /**
     * Initializes the AWS Connection.
     *
     * @return AWS Instance
     */
    protected function initConnection()
    {
        $config = Yii::$app->aws->config;

        if (!$config) {
            $config = [];
        }

        $aws = Aws::factory($config);

        // Attach an event listener that will append the Yii2 version number in the user agent string
        $aws->getEventDispatcher()->addListener('service_builder.create_client', array($this, 'onCreateClient'));

        return $aws;
    }

    /**
     * Add Yii version in UserAgent (used for metrics)
     *
     * @param  Event $event The event containing the instantiated client object
     *
     * @return void
     */
    public function onCreateClient(Event $event)
    {
        $version = \Yii::getVersion();

        $clientConfig  = $event['client']->getConfig();
        $commandParams = $clientConfig->get(Client::COMMAND_PARAMS) ?: array();
        $clientConfig->set(Client::COMMAND_PARAMS, array_merge_recursive($commandParams, array(
            UserAgentListener::OPTION => 'YII2/' . $version . ' YII2MOD/' . $version,
        )));
    }
}
