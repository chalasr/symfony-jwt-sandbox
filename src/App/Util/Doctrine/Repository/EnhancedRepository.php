<?php

namespace App\Util\Doctrine\Repository;

use App\Util\Doctrine\Entity\AbstractEntity;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Abstract Repository.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class EnhancedRepository extends EntityRepository
{
    /**
     * Handles fail in find method.
     *
     * @param int $id
     *
     * @throws NotFoundHttpException
     *
     * @return AbstractEntity Instance of AbstactEntity
     */
    public function findOrFail($id)
    {
        if (null == $entity = $this->find($id)) {
            return $this->resourceNotFound($id);
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
     * @throws NotFoundHttpException
     *
     * @return AbstractEntity Instance of AbstactEntity
     */
    public function findByOrFail(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $entities = $this->findBy($criteria, $orderBy);

        if (count($entities) == 0) {
            return $this->resourceNotFound();
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
     * @throws NotFoundHttpException
     *
     * @return AbstractEntity Instance of AbstactEntity
     */
    public function findOneByOrFail(array $criteria, array $orderBy = null)
    {
        $entity = $this->findOneBy($criteria, $orderBy);

        if (null == $entity) {
            return $this->resourceNotFound();
        }

        return $entity;
    }

    /**
     * Check for existing resource by criteria and fail.
     *
     * @param array $criteria
     * @param mixed $orderBy
     * @param mixed $limit
     * @param mixed $offset
     *
     * @throws UnprocessableEntityHttpException
     *
     * @return object
     */
    public function findOneByAndFail(array $criteria, array $orderBy = null)
    {
        $entity = $this->findOneBy($criteria, $orderBy);

        if (null !== $entity) {
            return $this->resourceAlreadyExists($entity->getId());
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
        $entity = $this->findOneBy($criteria, $orderBy);

        if (null == $entity) {
            return $this->create($criteria);
        }

        return $entity;
    }

    /**
     * Fails on resource not found.
     *
     * @param mixed  $id
     * @param string $message
     *
     * @throws NotFoundHttpException
     */
    public function resourceNotFound($id = null, $message = 'Unable to find resource')
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
    public function resourceAlreadyExists($id = null, $message = 'A resource already exists')
    {
        if (null !== $id) {
            $endMessage = sprintf(' with id %d', $id);
            $message .= $endMessage;
        }

        throw new UnprocessableEntityHttpException($message);
    }

    /**
     * Creates a new resource.
     *
     * @param array $properties
     *
     * @return EntityInterface
     */
    public function create(array $properties)
    {
        $entity = new $this->_entityName();

        foreach ($properties as $property => $value) {
            $setter = 'set'.ucfirst($property);
            $entity->$setter($value);
        }

        $this->_em->persist($entity);
        $this->_em->flush();

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
    public function update(AbstractEntity $entity, array $properties)
    {
        foreach ($properties as $property => $value) {
            $setter = 'set'.ucfirst($property);
            $entity->$setter($value);
        }

        $this->_em->flush();

        return $entity;
    }

    /**
     * Deletes a resource.
     *
     * @param EntityInterface $entity
     */
    public function delete(AbstractEntity $entity)
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }
}
