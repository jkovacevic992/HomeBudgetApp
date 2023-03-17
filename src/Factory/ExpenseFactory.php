<?php

namespace App\Factory;

use App\Entity\Expense;

class ExpenseFactory
{
    /**
     * @return Expense
     */
    public static function create(): Expense
    {
        return new Expense();
    }
}