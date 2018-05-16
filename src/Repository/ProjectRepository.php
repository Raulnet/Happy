<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 14/05/18
 * Time: 23:48.
 */

namespace Happy\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Happy\Entity\Project;

/**
 * Class ProjectRepository.
 */
class ProjectRepository extends ServiceEntityRepository
{
    /**
     * ProjectRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Project::class);
    }
}