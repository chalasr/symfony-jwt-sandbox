<?php

/*
 * This file is part of the Importer package.
 *
 * (c) Robin Chalas <robin.chalas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RCH\Importer\Reader;

interface ReaderInterface
{
    /**
     */
    public function open();

    /**
     */
    public function read();

    /**
     */
    public function close();
}
