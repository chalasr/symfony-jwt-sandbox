<?php

namespace App\Util\Controller;

use JMS\Serializer\SerializerBuilder;

/**
 * Add serialization features.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
trait EntitySerializableTrait
{
    /**
     * Serialize an entity or other object in given format.
     *
     * @param object $object The object to serialize
     * @param string $format FOSRestBundle default format
     *
     * @return string The serialized object
     */
    protected function serialize($object, $format = 'json')
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize($object, $format);
    }
}
