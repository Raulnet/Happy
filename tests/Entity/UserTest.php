<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 14/05/18
 * Time: 23:08.
 */

namespace Happy\Tests\Entity;

use Happy\Entity\Project;
use Happy\Entity\User;
use Happy\Tests\AbstractTestCase;

/**
 * Class UserTest.
 */
class UserTest extends AbstractTestCase
{
    public function testGetPassword(): void
    {
        $user = new User();
        $this->assertNull($user->getPassword());
    }

    public function testGetSalt(): void
    {
        $user = new User();
        $this->assertNull($user->getSalt());
    }

    public function testEraseCredentials(): void
    {
        $user = new User();
        $this->assertNull($user->eraseCredentials());
    }

    public function testAddRemoveRole(): void
    {
        // init user
        $user = new User();
        $this->assertTrue(in_array('ROLE_USER', $user->getRoles()));
        // test AddRole
        $user->addRole('ROLE_ADMIN');
        $this->assertTrue(in_array('ROLE_USER', $user->getRoles()));
        $this->assertTrue(in_array('ROLE_ADMIN', $user->getRoles()));
        // test RemoveRole
        $user->removeRole('ROLE_USER');
        $this->assertFalse(in_array('ROLE_USER', $user->getRoles()));
        $this->assertTrue(in_array('ROLE_ADMIN', $user->getRoles()));
        $user->removeRole('ROLE_ADMIN');
        $this->assertFalse(in_array('ROLE_USER', $user->getRoles()));
        $this->assertFalse(in_array('ROLE_ADMIN', $user->getRoles()));
        $this->assertTrue(empty($user->getRoles()));
    }

    public function testGetUsername(): void
    {
        $user = new User();
        $user->setId('id');
        $this->assertTrue('id' === $user->getUsername());
    }

    public function testAddRemoveProject()
    {
        // init user
        $user = new User();
        $this->assertTrue($user->getProjects()->isEmpty());
        // test addProject
        $project1 = new Project();
        $user->addProject($project1);
        $this->assertTrue(1 === $user->getProjects()->count());
        $project2 = new Project();
        $user->addProject($project2);
        $this->assertTrue(2 === $user->getProjects()->count());
        // test removeProject
        $user->removeProject($project2);
        $this->assertTrue(1 === $user->getProjects()->count());
        $this->assertTrue($user->getProjects()->contains($project1));
        $this->assertFalse($user->getProjects()->contains($project2));
    }
}