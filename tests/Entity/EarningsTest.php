<?php

namespace Entity;

use App\Entity\Earnings;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class EarningsTest extends TestCase
{
    /**
     * @return void
     */
    public function testEarningsCreate(): void
    {
        $earnings = new Earnings();
        $user = new User();
        $datetime = new \DateTimeImmutable('now');
        $earnings->setUser($user);
        $earnings->setDescription('Test description.');
        $earnings->setAmount(200.55);
        $earnings->setCreatedAt($datetime);

        $this->assertEquals($user, $earnings->getUser());
        $this->assertEquals('Test description.', $earnings->getDescription());
        $this->assertEquals($datetime, $earnings->getCreatedAt());
        $this->assertEquals(200.55,$earnings->getAmount());
    }
}