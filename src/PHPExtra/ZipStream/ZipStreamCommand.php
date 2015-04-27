<?php

namespace PHPExtra\ZipStream;

/**
 * The ZipStreamCommand class
 *
 * @author Jacek Kobus <kobus.jacek@gmail.com>
 */
class ZipStreamCommand
{
    /**
     * @var array
     */
    private $files;

    /**
     * @param array $files
     */
    function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * @return string
     */
    public function prepareCommand()
    {
        $fileNames = $this->getAbsoluteFilenames($this->files);
        $escapedFileNames = $this->escapeFilenames($fileNames);

        return sprintf('zip -0 -j -q -r - %s', implode(' ', $escapedFileNames));
    }

    /**
     * @param array $files
     *
     * @return array
     */
    private function getAbsoluteFilenames(Array $files)
    {
        $absoluteFilenames = array();

        foreach ($files as $file) {
            $realFile = realpath($file);

            if ($realFile === false) {
                throw new \RuntimeException(sprintf('File does not exist: %s', $file));
            }
            $absoluteFilenames[] = $realFile;
        }

        return $absoluteFilenames;
    }

    /**
     * @param array $fileNames
     *
     * @return array
     */
    private function escapeFilenames(Array $fileNames)
    {
        foreach ($fileNames as $fileNo => $fileName) {
            $fileNames[$fileNo] = self::escapeArgument($fileName);
        }

        return $fileNames;
    }

    /**
     * Escapes a string to be used as a shell argument.
     *
     * @param string $argument The argument that will be escaped
     *
     * @return string The escaped argument
     */
    private static function escapeArgument($argument)
    {
        //Fix for PHP bug #43784 escapeshellarg removes % from given string
        //Fix for PHP bug #49446 escapeshellarg doesn't work on Windows
        //@see https://bugs.php.net/bug.php?id=43784
        //@see https://bugs.php.net/bug.php?id=49446
        if ('\\' === DIRECTORY_SEPARATOR) {
            if ('' === $argument) {
                return escapeshellarg($argument);
            }

            $escapedArgument = '';
            $quote = false;
            foreach (preg_split('/(")/', $argument, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE) as $part) {
                if ('"' === $part) {
                    $escapedArgument .= '\\"';
                } elseif (self::isSurroundedBy($part, '%')) {
                    // Avoid environment variable expansion
                    $escapedArgument .= '^%"'.substr($part, 1, -1).'"^%';
                } else {
                    // escape trailing backslash
                    if ('\\' === substr($part, -1)) {
                        $part .= '\\';
                    }
                    $quote = true;
                    $escapedArgument .= $part;
                }
            }
            if ($quote) {
                $escapedArgument = '"'.$escapedArgument.'"';
            }

            return $escapedArgument;
        }

        return escapeshellarg($argument);
    }

    private static function isSurroundedBy($arg, $char)
    {
        return 2 < strlen($arg) && $char === $arg[0] && $char === $arg[strlen($arg) - 1];
    }
}