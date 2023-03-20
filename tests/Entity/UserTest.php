<?php

namespace Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    /**
     * @return void
     */
    public function testCreateUser(): void
    {
        $user = new User();
        $user->setBalance(500);
        $user->setEmail('user@example.com');
        $user->setPassword('password123');

        $this->assertEquals(500, $user->getBalance());
        $this->assertEquals('user@example.com', $user->getEmail());
        $this->assertEquals('password123', $user->getPassword());
    }

}