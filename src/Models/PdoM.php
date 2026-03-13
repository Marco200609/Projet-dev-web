<?php

namespace App\Models;

use PDO;

class PdoM
{
    protected PDO $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=172.25.241.71;dbname=bdd_site_web_a2_wsl;charset=utf8', 'phpstorm', 'PhpMy@dm1n');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}