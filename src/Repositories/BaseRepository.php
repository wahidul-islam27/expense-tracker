<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\App;
use App\DB;

abstract class BaseRepository {
    
    protected DB $db;

    public function __construct() {
        $this->db = App::db();
    }
}