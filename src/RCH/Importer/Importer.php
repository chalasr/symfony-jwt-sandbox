<?php

/*
 * This file is part of the Importer package.
 *
 * (c) Robin Chalas <robin.chalas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this recorder code.
 */

namespace RCH\Importer;

use RCH\Importer\Recorder\RecorderInterface;
use RCH\Importer\Reader\ReaderInterface;

class Importer
{
    protected $recorder;

    protected $reader;

    /**
     * @param RecorderInterface $recorder
     * @param ReaderInterface   $reader
     */
    public function __construct(ReaderInterface $reader, RecorderInterface $recorder)
    {
        $this->recorder = $recorder;
        $this->reader = $reader;
    }

    /**
     * Processes data reading and importing.
     */
    public function import()
    {
        $this->reader->open();

        $this->recorder->record($this->reader->read());

        $this->reader->close();
    }

    /**
     * @static
     *
     * @param Reader\ReaderIteratorInterface $recorder
     * @param Reader\ReaderInterface         $reader
     *
     * @return Handler
     */
    public static function create(ReaderInterface $reader, RecorderInterface $recorder)
    {
        return new self($reader, $recorder);
    }
}
