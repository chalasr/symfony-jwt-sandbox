<?php

/*
 * This file is part of the RCHImporter library.
 *
 * (c) Robin Chalas <robin.chalas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RCH\Importer\Recorder;

/**
 * Contract of Recorder.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
interface RecorderInterface
{
    /**
     * Processes data recording to database.
     */
    public function record(array $data);

    /**
     * Add an entry to the EntityManager peristed queue.
     */
    public function addEntry(array $entry);
}
