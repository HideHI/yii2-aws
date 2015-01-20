<?php


use jambroo\aws\filter\file\S3RenameUpload;
use Aws\Common\Aws;
use Aws\S3\S3Client;
use \ReflectionMethod;

use jambroo\aws\factory\AwsFactory;
use jambroo\aws\view\helper\S3Link;
use jambroo\aws\view\exception\InvalidSchemeException;
use jambroo\aws\view\exception\InvalidDomainNameException;


class S3RenameUploadTest extends \Codeception\TestCase\Test
{
    /**
     * @var S3RenameUpload
     */
    protected $filter;

    public function setUp()
    {
       	$s3Client = S3Client::factory(array(
            'key'    => '1234',
            'secret' => '5678'
        ));

        $this->filter = new S3RenameUpload($s3Client);
    }

    public function testAssertFilterAlwaysRegistersS3StreamWrapper()
    {
        $this->assertContains('s3', stream_get_wrappers());
    }

    public function testThrowExceptionIfNoBucketIsSet()
    {
        $this->setExpectedException('jambroo\aws\filter\exception\MissingBucketException');
        $this->filter->filter(array('tmp_name' => 'foo'));
    }

    /**
     * @dataProvider tmpNameProvider
     */
    public function testAssertS3UriIsGenerated($tmpName, $expectedKey)
    {
        $reflMethod = new ReflectionMethod($this->filter, 'getFinalTarget');
        $reflMethod->setAccessible(true);

        $this->filter->setBucket('my-bucket');

        $result = $reflMethod->invoke($this->filter, array(
            'tmp_name' => $tmpName
        ));

        $this->assertEquals("s3://my-bucket/{$expectedKey}", $result);
    }

    public function tmpNameProvider()
    {
        return array(
            array('temp/phptmpname', 'temp/phptmpname'),
            array('temp/phptmpname/', 'temp/phptmpname'),
            array('temp\\phptmpname', 'temp/phptmpname'),
            array('temp\\phptmpname\\', 'temp/phptmpname'),
        );
    }
}