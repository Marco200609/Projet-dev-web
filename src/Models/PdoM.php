<?php

namespace App\Models;

use PDO;

class PdoM
{
    protected PDO $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=172.28.6.112;dbname=bdd_site_web_a2_wsl;charset=utf8', 'web', 'Licorne1234&');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}