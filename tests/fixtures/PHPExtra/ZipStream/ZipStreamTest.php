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

    /**
     * @return array
     */
    public function testFileNamesProvider()
    {
        return array(
            array(array('test file.txt', "test%.txt", "john's_file.txt"))
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
        $this->assertEquals(517, strlen($stream->getContents()), 'Stream has wrong length');
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

    /**
     * @dataProvider testFileNamesProvider
     *
     * @param array $fileNames
     */
    public function testZipStreamInputFilesEscaping(array $fileNames)
    {
        $streamCommand = new \PHPExtra\ZipStream\ZipStreamCommand($fileNames);
        $createCommandMethod = $this->getZipStreamCommandMethod('escapeFilenames');
        $fileNames = $createCommandMethod->invokeArgs($streamCommand, array($fileNames));

        $this->assertEquals($fileNames, array("'test file.txt'", "'test%.txt'", "'john'\\''s_file.txt'"));
    }

    /**
     * @param string $name
     * @return ReflectionMethod
     */
    private function getZipStreamCommandMethod($name)
    {
        $class = new ReflectionClass('\PHPExtra\ZipStream\ZipStreamCommand');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}