<?php

namespace RCH\Importer\Reader;

use Importer\Exception\InvalidDataFormatException;

class CsvReader implements ReaderInterface
{
    protected $filename;

    protected $hasHeaders;

    /**
     * @param string $filename
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @param bool   $hasHeaders
     * @param bool   $withBom
     */
    public function __construct($filename, $hasHeaders = true)
    {
        $this->filename    = $filename;
        $this->hasHeaders  = $hasHeaders;

        if (false === is_file($filename)) {
            throw new \RuntimeException(sprintf('Unable to find file %s', $filename));
        }

        if (false === is_readable($filename)) {
            throw new \RuntimeException(sprintf('The file %s is not readable.', $filename));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function open()
    {
        $this->file = fopen($this->filename, 'r');
    }

    /**
     * {@inheritdoc}
     */
    public function read()
    {
        $rows = array();
        $header = null;
        $countRows = 0;

        while ($row = fgetcsv($this->file, null, ',', '"')) {
            ++$countRows;

            if (true === $this->hasHeaders && $header === null) {
                 $header = $row;
                 continue;
            }
            $rows[] = array_combine($header, $row);
        }

        return $rows;
    }

    /**
    * {@inheritdoc}
    */
    public function close()
    {
      fclose($this->file);
    }
}
