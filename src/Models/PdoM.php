<?php

namespace App\Models;

use PDO;

class PdoM
{
    protected PDO $pdo;

    public function __construct() {

        $env = parse_ini_file(__DIR__ .'/../../Config/.env');

        $this->pdo = new PDO('mysql:host='.$env['HOST'].';dbname='.$env['DBNAME'].';port='.$env['PORT'].';charset=utf8',  $env['USER'],  $env['PASS']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}