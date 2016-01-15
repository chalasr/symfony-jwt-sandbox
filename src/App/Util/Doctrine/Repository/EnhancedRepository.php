<?php

namespace App\Util\Doctrine\Repository;

use App\Util\Doctrine\Entity\AbstractEntity;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Enhanced EntityRepository that pre-handle exceptions.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class EnhancedRepository extends EntityRepository implements ObjectRepository
{
    const NOT_FOUND_MESSAGE = 'The resource cannot be found';

    const ALREADY_EXISTS_MESSAGE = 'A resource already exists';

    /**
     * Finds a resource by identifier.
     *
     * @param int      $id          The resource identifier
     * @param int|null $lockMode    One of the \Doctrine\DBAL\LockMode::* constants or NULL
     * @param int|null $lockVersion The lock version.
     *
     * @throws NotFoundHttpException
     *
     * @return object
     */
    public function findOrFail($id, $lockMode = null, $lockVersion = null)
    {
        $entity = $this->find($id);

        if (null === $entity) {
            throw new NotFoundHttpException(
                sprintf('%s (\'id\' = %d)', self::NOT_FOUND_MESSAGE, $id)
            );
        }

        return $entity;
    }

    /**
     * Find resource by criteria or fail.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @throws NotFoundHttpException
     *
     * @return object
     */
    public function findByOrFail(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $entities = $this->findBy($criteria, $orderBy);

        if (count($entities) == 0) {
            throw new NotFoundHttpException(
                sprintf('%s (%s)', self::NOT_FOUND_MESSAGE, $this->implodeCriteria($criteria))
            );
        }

        return $entities;
    }

    /**
     * Find resource by criteria or create a new.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @throws NotFoundHttpException
     *
     * @return object
     */
    public function findOneByOrFail(array $criteria, array $orderBy = null)
    {
        $entity = $this->findOneBy($criteria, $orderBy);

        if (null === $entity) {
            throw new NotFoundHttpException(
                sprintf('%s (%s)', self::NOT_FOUND_MESSAGE, $this->implodeCriteria($criteria))
            );
        }

        return $entity;
    }

    /**
     * Check for existing resource by criteria and fail.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @throws UnprocessableEntityHttpException
     *
     * @return object
     */
    public function findOneByAndFail(array $criteria, array $orderBy = null)
    {
        $entity = $this->findOneBy($criteria, $orderBy);

        if (null !== $entity) {
            throw new UnprocessableEntityHttpException(
                sprintf('%s (%s)', self::ALREADY_EXISTS_MESSAGE, $this->implodeCriteria($criteria))
            );
        }

        return $entity;
    }

    /**
     * Find resource by criteria or create a new.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return object
     */
    public function findOneByOrCreate(array $criteria, array $orderBy = null)
    {
        $entity = $this->findOneBy($criteria, $orderBy);

        if (null === $entity) {
            return $this->create($criteria);
        }

        return $entity;
    }

    /**
     * Creates a new resource.
     *
     * @param array $properties
     *
     * @return object
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
     * @param AbstractEntity $entity
     * @param array          $properties
     *
     * @return object
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
     * Deletes a given resource.
     *
     * @param AbstractEntity $entity
     */
    public function delete(AbstractEntity $entity)
    {
        $this->_em->remove($entity);
        $this->_em->flush();
    }

    /**
     * Get criteria as string.
     *
     * @param array $criteria The query criteria
     *
     * @return string
     */
    private function implodeCriteria(array $criteria)
    {
        if (true === empty($criteria)) {
            return 'no fields';
        }

        $keys = implode(', ', array_keys($criteria));
        $values = implode(', ', array_values($criteria));

        return sprintf("%s' = '%s'", $keys, $values);
    }
}
