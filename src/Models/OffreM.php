<?php

namespace App\Models;

use PDO;

class OffreM extends PdoM
{

    public function getNbOffre()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM offre");
        return (int)$stmt->fetchColumn();
    }
    public function getOffres($page, $parpage, $nom = '', $ville = '') : array
    {
        if ($parpage ==-1) {
            $parpage = $this->getNbEntreprises();
        }
        $start = ($page - 1) * $parpage;

    }
}