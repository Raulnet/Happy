<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 14/05/18
 * Time: 23:49.
 */

namespace Happy\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Happy\Entity\User;

/**
 * Class UserRepository.
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }
}