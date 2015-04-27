<?php

namespace PHPExtra\ZipStream;

use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Stream\StreamInterface;

/**
 * The ZipStream class
 *
 * @author Jacek Kobus <kobus.jacek@gmail.com>
 */
class ZipStream implements StreamInterface
{
    /**
     * @var array
     */
    private $files;

    /**
     * @var array
     */
    private $pipes;

    /**
     * @var resource
     */
    private $process;

    /**
     * @var StreamInterface
     */
    private $stream = null;

    /**
     * @param array $files
     */
    function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * @return StreamInterface
     */
    protected function getStream()
    {
        if ($this->stream === null) {
            $descriptors = array(
                0 => array('pipe', 'r'),    // stdin
                1 => array('pipe', 'w'),    // stdout
                2 => array('pipe', 'a')     // stderr
            );

            $zipStreamCommand = new ZipStreamCommand($this->files);
            $command = $zipStreamCommand->prepareCommand();
            $this->process = proc_open($command, $descriptors, $this->pipes);

            if ($this->process === false) {
                throw new \RuntimeException(sprintf('Unable to create process: %s', $command));
            }

            $this->stream = Stream::factory($this->pipes[1]);
        }

        return $this->stream;
    }

    /**
     * Create new ZipStream
     *
     * @param array $files
     *
     * @return $this
     */
    public static function create(array $files)
    {
        return new self($files);
    }

    /**
     * @return void
     */
    public function close()
    {
        $this->getStream()->close();
        if ($this->process) {
            proc_close($this->process);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable()
    {
        return $this->getStream()->isSeekable();
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable()
    {
        return $this->getStream()->isWritable();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getStream()->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        return $this->getStream()->detach();
    }

    /**
     * {@inheritdoc}
     */
    public function attach($stream)
    {
        $this->getStream()->attach($stream);
    }

    /**
     * {@inheritdoc}
     */
    public function getSize()
    {
        return $this->getStream()->getSize();
    }

    /**
     * {@inheritdoc}
     */
    public function tell()
    {
        return $this->getStream()->tell();
    }

    /**
     * {@inheritdoc}
     */
    public function eof()
    {
        return $this->getStream()->eof();
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        return $this->getStream()->seek($offset, $whence);
    }

    /**
     * {@inheritdoc}
     */
    public function write($string)
    {
        return $this->getStream()->write($string);
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable()
    {
        return $this->getStream()->isReadable();
    }

    /**
     * {@inheritdoc}
     */
    public function read($length)
    {
        return $this->getStream()->read($length);
    }

    /**
     * {@inheritdoc}
     */
    public function getContents()
    {
        return $this->getStream()->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata($key = null)
    {
        return $this->getStream()->getMetadata();
    }
}