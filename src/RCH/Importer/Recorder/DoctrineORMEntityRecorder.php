<?php

/*
 * This file is part of the Importer package.
 *
 * (c) Robin Chalas <robin.chalas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RCH\Importer\Recorder;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use RCH\Importer\Util\ConvertibleTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Records entries through \Doctrine\ORM\EntityManager.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class DoctrineORMEntityRecorder implements RecorderInterface
{
    /** @var string */
    protected $className;

    /** @var EntityManager */
    protected $entityManager;

    /** @var array */
    protected $keyMap;

    /** @var array */
    protected $converters;

    /** @var string */
    protected $class;

    /** @var int */
    protected $frequency = 20;

    /**
     * Constructor.
     *
     * @param string        $className The Entity class name.
     * @param EntityManager $entityMangager
     * @param array
     */
    public function __construct($className, EntityManager $entityManager, array $keyMap = array(), array $converters = null)
    {
        $this->className = $className;
        $this->em = $entityManager;
        $this->keyMap = $keyMap;
        $this->converters = $converters;
        $this->class = $entityManager->getClassMetadata($className);
    }

    /**
     * {@inheritdoc}
     */
    public function record(array $data)
    {
        $this->em->getConnection()
            ->getConfiguration()
            ->setSQLLogger(null);
        $count = 0;

        if (count($data) < 1) {
            return;
        }

        if ($this->converters) {
            $data = $this->convertValues($data);
        }

        if (!$this->keyMap) {
            foreach ($data[0] as $key => $value) {
                $this->keyMap[$key] = $key;
            }
        }

        foreach ($data as $entry) {
            $this->addEntry($entry);
            ++$count;

            if (($count % $this->frequency) === 0) {
                $this->em->flush();
                $this->em->clear();
            }
        }

        $this->em->flush();
        $this->em->clear();
    }

    /**
     * Add an entry to the EntityManager persisted queue.
     *
     * @param array $entry The newly created object
     *
     * @return void
     */
    public function addEntry(array $entry)
    {
        if (!$entry) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $namespace = $this->class->getName();
        $mappedFields = $this->class->getFieldNames();
        $mappedAssociations = $this->class->getAssociationNames();

        $object = new $namespace();

        if (isset($entry['id'])) {
            unset($entry['id']);
        }

        foreach ($entry as $field => $value) {
            $key = $this->keyMap[$field];

            if (!in_array($key, $mappedFields) || in_array($key, $mappedAssociations)) {
                continue;
            }

            // Must be hard-coded for format.
            if ($this->class->getTypeOfField($key) == 'date') {
                $value = new \DateTime();
            }

            if (method_exists($object, 'setPlainPassword')) {
                $object->setPlainPassword('test');
            }

            $accessor->setValue($object, $key, $value);
        }

        $this->em->persist($object);
    }
}
