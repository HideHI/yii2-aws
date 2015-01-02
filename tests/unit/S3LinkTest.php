<?php

use Aws\Common\Aws;
use Aws\S3\S3Client;
use jambroo\aws\factory\AWSFactory;
use jambroo\aws\view\helper\S3Link;

class S3LinkTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var S3Client
     */
    protected $s3Client;

    /**
     * @var S3Link
     */
    protected $viewHelper;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
print_r(get_declared_classes()); exit;
        parent::setUp();

        $this->s3Client = S3Client::factory(array(
            'key'    => '1234',
            'secret' => '5678'
        ));
        
	$this->viewHelper = new S3Link($this->s3Client);
    }
    
    /*protected function _before()
    {
    }

    protected function _after()
    {
    }*/

    public function testSSL()
    {
        $this->assertEquals('https', $this->viewHelper->getScheme());
    }

}

