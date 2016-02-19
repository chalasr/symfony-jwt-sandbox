<?php

namespace Util\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;

/**
 * Add serialization features.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
trait CanSerializeTrait
{
    /**
     * Serialize an entity or other object in given format.
     *
     * @param object $object  The object to serialize
     * @param array  $options Context options
     *
     * @return string The serialized object
     */
    protected function serialize($object, array $options = array())
    {
        $default = array(
            'format'         => 'json',
            'serialize_null' => true,
        );

        // Prepare Serializer and Context
        $options = array_replace($default, $options);
        $serializer = SerializerBuilder::create()->build();
        $context = SerializationContext::create();

        // Serialize properties with a null value
        $context->setSerializeNull($options['serialize_null']);

        // Add groups to Serializer
        if (true === isset($options['groups'])) {
            $groups = $options['groups'];
            $context->setGroups(!is_array($groups) ? [$groups] : $groups);
        }

        return $serializer->serialize($object, $options['format'], $context);
    }
}
