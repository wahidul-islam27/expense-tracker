<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Repositories\Repository;

interface UserRepositoryInterface extends Repository {
    public function updateMonthlyIncome($id, $income);
    public function getByUsernameAndPassword($username, $password);
    public function updatePassword($username, $password);
}