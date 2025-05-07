<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Repositories\BaseRepository;

interface Repository {
    public function create($data);
    public function get($id);
    public function update($id, $data);
    public function delete($id);
    public function getAll();
}