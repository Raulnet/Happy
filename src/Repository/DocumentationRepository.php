<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 14/05/18
 * Time: 23:43.
 */

namespace Happy\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Happy\Entity\Documentation;

/**
 * Class DocumentationRepository.
 */
class DocumentationRepository extends ServiceEntityRepository
{
    /**
     * DocumentationRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Documentation::class);
    }
}