<?php

use Aws\CloudFront\CloudFrontClient;
use jambroo\aws\view\helper\CloudFrontLink;

use jambroo\aws\view\exception\InvalidSchemeException;
use jambroo\aws\view\exception\InvalidDomainNameException;

class CloudFrontLinkTest extends \Codeception\TestCase\Test
{
    /**
     * @var CloudFrontClient
     */
    protected $cloudFrontClient;
    /**
     * @var CloudFrontLink
     */
    protected $viewHelper;
    
    public function setUp()
    {
        $this->cloudFrontClient = CloudFrontClient::factory(array(
            'key'    => '1234',
            'secret' => '5678',
        ));
        $this->viewHelper = new CloudFrontLink($this->cloudFrontClient);
    }
    public function testAssertDoesUseSslByDefault()
    {
        $this->assertTrue($this->viewHelper->getUseSsl());
    }
}
