<?php

namespace Entity;

use App\Entity\Category;
use App\Entity\Expense;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ExpenseTest extends TestCase
{
    public function testExpenseCreate(): void
    {
        $expense = new Expense();
        $datetime = new \DateTimeImmutable('now');
        $user = new User();
        $category = new Category();
        $expense->setCreatedAt($datetime);
        $expense->setAmount(200.00);
        $expense->setDescription('Test description.');
        $expense->setUser($user);
        $expense->setCategory($category);

        $this->assertEquals($datetime, $expense->getCreatedAt());
        $this->assertEquals(200.00, $expense->getAmount());
        $this->assertEquals($user, $expense->getUser());
        $this->assertEquals($category, $expense->getCategory());
        $this->assertEquals('Test description.', $expense->getDescription());
    }
}