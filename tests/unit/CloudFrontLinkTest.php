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

    /**
     * @expectedException \jambroo\aws\view\exception\InvalidSchemeException
     */
    public function testAssertInvalidSchemesThrowExceptions()
    {
        $this->viewHelper->setScheme('nosuchscheme');
    }
    public function testGenerateSimpleLink()
    {
        $link = $this->viewHelper->__invoke('my-object', 'my-domain');
        $this->assertEquals('https://my-domain.cloudfront.net/my-object', $link);
    }
    public function testGenerateSimpleNonSslLink()
    {
        $this->viewHelper->setScheme('http');
        $link = $this->viewHelper->__invoke('my-object', 'my-domain');
        $this->assertEquals('http://my-domain.cloudfront.net/my-object', $link);
    }
    public function testGenerateSimpleProtocolRelativeLink()
    {
        $this->viewHelper->setScheme(null);
        $link = $this->viewHelper->__invoke('my-object', 'my-domain');
        $this->assertEquals('//my-domain.cloudfront.net/my-object', $link);
    }
    public function testCanUseDefaultDomain()
    {
        $this->viewHelper->setDefaultDomain('my-default-domain');
        $link = $this->viewHelper->__invoke('my-object');
        $this->assertEquals('https://my-default-domain.cloudfront.net/my-object', $link);
    }
}
