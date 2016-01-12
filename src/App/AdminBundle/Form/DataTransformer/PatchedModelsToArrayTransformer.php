<?php

namespace App\AdminBundle\Form\DataTransformer;

use Sonata\AdminBundle\Form\DataTransformer\ModelsToArrayTransformer;

/**
 * Class ModelsToArrayTransformer.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class PatchedModelsToArrayTransformer extends ModelsToArrayTransformer
{
    /**
     * ModelsToArrayTransformer constructor.
     *
     * @param ModelChoiceList       $choiceList
     * @param ModelManagerInterface $modelManager
     * @param                       $class
     */
    public function __construct(ModelChoiceList $choiceList, ModelManagerInterface $modelManager, $class)
    {
        $this->choiceList   = $choiceList;
        $this->modelManager = $modelManager;
        $this->class        = $class;
    }
}
