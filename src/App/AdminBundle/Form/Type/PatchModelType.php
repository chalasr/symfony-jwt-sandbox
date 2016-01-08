<?php

namespace App\AdminBundle\Form\Type;

use App\AdminBundle\Form\DataTransformer\PatchedModelsToArrayTransformer;
use Sonata\AdminBundle\Form\Type\ModelType;

class PatchedModelType extends ModelType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['multiple']) {
            if (array_key_exists('choice_loader', $options) && $options['choice_loader'] !== null) {
                $builder->addViewTransformer(new PatchedModelsToArrayTransformer($options['choice_list'], $options['model_manager'], $options['class']), true);
            } else {
                $builder->addViewTransformer(new LegacyModelsToArrayTransformer($options['choice_list']), true);
            }

            $builder
                ->addEventSubscriber(new MergeCollectionListener($options['model_manager']))
            ;
        } else {
            $builder
                ->addViewTransformer(new ModelToIdTransformer($options['model_manager'], $options['class']), true)
            ;
        }
    }
}
