<?php

namespace jambroo\aws\tests\codeception\unit\view\helper;

use Yii;

use Aws\S3\S3Client;
use jambroo\aws\view\helper\S3Link;

use PHPUnit_Framework_TestCase;

class S3LinkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var S3Client
     */
    protected $s3Client;

    /**
     * @var S3Link
     */
    protected $viewHelper;

    protected function setUp()
    {
        parent::setUp();
    
        $this->s3Client = S3Client::factory(array(
            'key'    => '1234',
            'secret' => '5678'
        ));

        $this->viewHelper = new S3Link($this->s3Client);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testAssertUseSslByDefault()
    {
        $this->assertEquals('https', $this->viewHelper->getScheme());
    }
}

