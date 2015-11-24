<?php

/**
 * The ZipStreamTest class
 *
 * @author Jacek Kobus <kobus.jacek@gmail.com>
 */
class ZipStreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function testFilesProvider()
    {
        return array(
            array(include __DIR__ . '/../../../testfiles/testfiles.php')
        );
    }

    public function testCreateZipStreamInstance()
    {
        new \PHPExtra\ZipStream\ZipStream(array());
    }

    /**
     * @dataProvider testFilesProvider
     *
     * @param array $files
     */
    public function testCreateNewZipStreamFromTestFiles(array $files)
    {
        $stream = new \PHPExtra\ZipStream\ZipStream($files);
        $this->assertEquals(714, strlen($stream->getContents()), 'Stream has wrong length');
    }

    /**
     * @dataProvider testFilesProvider
     *
     * @param array $files
     */
    public function testCreateZipStreamInstanceUsingFactoryMethod(array $files)
    {
        $stream = \PHPExtra\ZipStream\ZipStream::create($files);
        $this->assertInstanceOf('\PHPExtra\ZipStream\ZipStream', $stream);
    }

    /**
     * @dataProvider testFilesProvider
     *
     * @param array $files
     */
    public function testAddFileIntoZipStream(array $files)
    {
        $stream = new \PHPExtra\ZipStream\ZipStream($files);
        $stream->addFile(__DIR__ . '/../../../testfiles/file4.txt');
        $this->assertEquals(879, strlen($stream->getContents()), 'Stream has wrong length');
    }

    public function testAddWildcardIntoZipStream()
    {
        $stream = new \PHPExtra\ZipStream\ZipStream();
        $stream->addFilesByWildcard(__DIR__ . '/../../../testfiles/*.txt');
        $this->assertEquals(879, strlen($stream->getContents()), 'Stream has wrong length');
    }

    /**
     * @dataProvider testFilesProvider
     *
     * @param array $files
     */
    public function testZipStreamIsNotSeekable(array $files)
    {
        $stream = \PHPExtra\ZipStream\ZipStream::create($files);
        $this->assertFalse($stream->isSeekable());
    }

    /**
     * @dataProvider testFilesProvider
     *
     * @param array $files
     */
    public function testZipStreamIsNotWritable(array $files)
    {
        $stream = \PHPExtra\ZipStream\ZipStream::create($files);
        $this->assertFalse($stream->isWritable());
    }

}
