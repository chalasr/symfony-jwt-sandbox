<?php

namespace App\Util\Doctrine\Repository;

use App\Util\Doctrine\Entity\AbstractEntity;
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
            return self::notFound($id);
        }

        return $entity;
    }

    /**
     * Find resource by criteria or fail.
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
            return self::notFound();
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
        $entity = $this->findOneBy($criteria, $orderBy);

        if (null == $entity) {
            return self::notFound();
        }

        return $entity;
    }

    /**
     * Check for existing resource by criteria and fail
     *
     * @param array $criteria
     * @param mixed $orderBy
     * @param mixed $limit
     * @param mixed $offset
     *
     * @return object
     */
    public function findOneByAndFail(array $criteria, array $orderBy = null)
    {
        $entity = $this->findOneBy($criteria, $orderBy);

        if (null !== $entity) {
            return self::alreadyExists($entity->getId());
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
    public function findOneByOrCreate(array $criteria, array $orderBy = null)
    {
        $entities = $this->findOneBy($criteria, $orderBy);

        if (count($entities) == 0) {
            return self::create($criteria);
        }

        return $entities;
    }

    /**
     * Check for existing resource by criteria.
     *
     * @param array $criteria
     * @param mixed $orderBy
     * @param mixed $limit
     * @param mixed $offset
     *
     * @return object
     */
    public function findByAndFail(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $entities = $this->findBy($criteria, $orderBy);

        if (count($entities) > 0) {
            return self::alreadyExists();
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
    public function findByOrCreate(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $entities = $this->findBy($criteria, $orderBy, $limit, $offset);

        if (count($entities) == 0) {
            return self::create($criteria);
        }

        return $entities;
    }

    /**
     * Fails on resource not found.
     *
     * @param mixed  $id
     * @param string $message
     *
     * @throws NotFoundHttpException
     */
    public static function notFound($id = null, $message = 'Unable to find resource')
    {
        if (null !== $id) {
            $endMessage = sprintf(' with id %d', $id);
            $message .= $endMessage;
        }

        throw new NotFoundHttpException($message);
    }

    /**
     * Fails on resource already exists.
     *
     * @param mixed  $id
     * @param string $message
     *
     * @throws NotFoundHttpException
     */
    public static function alreadyExists($id = null, $message = 'A resource already exists')
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
     * @return EntityInterface
     */
    public static function create(array $properties)
    {
        $entity = new self::$_className();

        foreach ($properties as $property => $value) {
            $setter = 'set'.ucfirst($property);
            $entity->$setter($value);
        }

        self::$_manager->persist($entity);
        self::$_manager->flush();

        return $entity;
    }

    /**
     * Updates an existing resource.
     *
     * @param EntityInterface $entity
     * @param array           $properties
     *
     * @return EntityInterface
     */
    public static function update(AbstractEntity $entity, array $properties)
    {
        foreach ($properties as $property => $value) {
            $setter = 'set'.ucfirst($property);
            $entity->$setter($value);
        }

        self::$_manager->flush();

        return $entity;
    }

    /**
     * Deletes a resource.
     *
     * @param EntityInterface $entity
     */
    public static function delete(AbstractEntity $entity)
    {
        self::$_manager->remove($entity);
        self::$_manager->flush();
    }
}
