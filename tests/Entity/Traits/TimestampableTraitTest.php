<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 14/05/18
 * Time: 22:38.
 */

namespace Happy\Tests\Entity\Traits;

use Happy\Entity\User;
use Happy\Tests\AbstractTestCase;

/**
 * Class TimestampableTraitTest.
 */
class TimestampableTraitTest extends AbstractTestCase
{
    /** @var \DateTime */
    private $datetime;

    public function setUp(): void
    {
        $this->datetime = new \DateTime('now');
    }

    public function testAutoUpdate(): void
    {
        $user = new User();
        $user->autoUpdate();
        $this->assertTrue($user->getDateCreation() instanceof \DateTime);
        $this->assertTrue($user->getDateUpdate() instanceof \DateTime);
    }

    public function testIsDeleted(): void
    {
        $user = new User();
        $this->assertFalse($user->isDeleted());
        $user->setDateDeleted($this->datetime);
        $this->assertTrue($user->isDeleted());
        $user->setDateDeleted(null);
        $this->assertFalse($user->isDeleted());
    }
}