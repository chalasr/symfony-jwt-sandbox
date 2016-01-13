<?php

namespace App\Util\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Abstract Repository.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class AbstractRepository extends EntityRepository
{
    /**
     * @var EntityManager
     */
    protected static $_manager;

    /**
     * @var string
     */
    protected static $_className;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager
     * @param ClassMetadata $class
     */
    public function __construct($entityManager, ClassMetadata $class)
    {
        parent::__construct($entityManager, $class);
        self::$_manager = $this->_em;
        self::$_className = $this->_entityName;
    }

    /**
     * Handles fail in find method.
     *
     * @param int $id
     *
     * @return object
     */
    public function findOrFail($id)
    {
        if (null == $entity = $this->find($id)) {
            return self::fail($id);
        }

        return $entity;
    }

    /**
     * Find resource by criteria or create a new.
     *
     * @param array $criteria
     * @param mixed $orderBy
     * @param mixed $limit
     * @param mixed $offset
     *
     * @return object
     */
    public function findByOrCreate(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $entities = $this->findBy($criteria, $orderBy, $limit, $offset);

        if (count($entities) == 0) {
            return self::create($criteria);
        }

        return $entities;
    }

    /**
     * Find resource by criteria or create a new.
     *
     * @param array $criteria
     * @param mixed $orderBy
     * @param mixed $limit
     * @param mixed $offset
     *
     * @return object
     */
    public function findByOrFail(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $entities = $this->findBy($criteria, $orderBy);

        if (count($entities) == 0) {
            return self::fail();
        }

        return $entities;
    }

    /**
     * Find resource by criteria or create a new.
     *
     * @param array $criteria
     * @param mixed $orderBy
     * @param mixed $limit
     * @param mixed $offset
     *
     * @return object
     */
    public function findOneByOrCreate(array $criteria, array $orderBy = null)
    {
        $entities = $this->findOneBy($criteria, $orderBy);

        if (count($entities) == 0) {
            return self::create($criteria);
        }

        return $entities;
    }

    /**
     * Find resource by criteria or create a new.
     *
     * @param array $criteria
     * @param mixed $orderBy
     * @param mixed $limit
     * @param mixed $offset
     *
     * @return object
     */
    public function findOneByOrFail(array $criteria, array $orderBy = null)
    {
        $entities = $this->findOneBy($criteria, $orderBy);

        if (count($entities) == 0) {
            return self::fail();
        }

        return $entities;
    }

    /**
     * Fails.
     *
     * @param mixed  $id
     * @param string $message
     *
     * @throws NotFoundHttpException
     */
    public static function fail($id = null, $message = 'Unable to find resource')
    {
        if (null !== $id) {
            $endMessage = sprintf(' with id %d', $id);
            $message .= $endMessage;
        }

        throw new NotFoundHttpException($message);
    }

    /**
     * Creates a new resource.
     *
     * @param array $properties
     *
     * @return int
     */
    public static function create(array $properties)
    {
        $entity = new self::$_className();

        foreach ($properties as $property => $value) {
            $setter = 'set' . ucfirst($property);
            $entity->$setter($value);
        }

        self::$_manager->persist($entity);
        self::$_manager->flush();

        return $entity;
    }
}
