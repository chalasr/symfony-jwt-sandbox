<?php

namespace App\Util\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Email as BaseEmailConstraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class Email extends BaseEmailConstraint
{
    public function __toString()
    {
        return 'Email';
    }
}
