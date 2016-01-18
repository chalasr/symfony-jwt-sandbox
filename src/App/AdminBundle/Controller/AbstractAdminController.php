<?php

namespace App\AdminBundle\Controller;

use App\Util\Controller\LocalizableTrait as Localizable;
use Sonata\AdminBundle\Controller\CRUDController as Controller;

/**
 * Abstract Admin Controller.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
abstract class AbstractAdminController extends Controller
{
    use Localizable;
}
