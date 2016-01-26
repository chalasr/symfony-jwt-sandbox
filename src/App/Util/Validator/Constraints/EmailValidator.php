<?php

namespace App\Util\Validator\Constraints;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\RuntimeException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraints\EmailValidator as BaseEmailValidator;

/**
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class EmailValidator extends BaseEmailValidator
{

}
