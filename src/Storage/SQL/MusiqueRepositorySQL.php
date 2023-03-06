<?php

namespace TheFeed\Storage\SQL;

use DateTime;
use Framework\Storage\Repository;
use PDO;
use TheFeed\Business\Entity\Musique;
use TheFeed\Business\Entity\Publication;
use TheFeed\Business\Entity\Utilisateur;

class MusiqueRepositorySQL implements Repository
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function get($id)
    {
        // TODO: Implement get() method.
    }

    public function create($musique)
    {
        $values = [
            "titre" => $musique->getTitre(),
            "date" => $musique->getDate()->format('Y-m-d H:i:s'),
            "fileMusique" => $musique->getUrlMusique(),
            "idAuteur" => $musique->getUtilisateur()->getIdUtilisateur()
        ];
        $statement = $this->pdo->prepare("INSERT INTO musiques (titre, date, fileMusique, idAuteur) VALUES(:titre, :date, :fileMusique, :idAuteur);");
        $statement->execute($values);
        return $this->pdo->lastInsertId();
    }

    public function update($entity)
    {
        // TODO: Implement update() method.
    }

    public function remove($entity)
    {
        // TODO: Implement remove() method.
    }

    public function getAllFrom($idUtilisateur) : array {
        $values = [
            "idAuteur" => $idUtilisateur,
        ];
        $statement = $this->pdo->prepare("SELECT idMusique, titre, date, fileMusique, idUtilisateur, login, profilePictureName 
                                                FROM musiques m 
                                                JOIN utilisateurs u on m.idAuteur = u.idUtilisateur
                                                WHERE idAuteur = :idAuteur                    
                                                ORDER BY date DESC");
        $statement->execute($values);

        $musiques = [];

        foreach ($statement as $data) {
            $musique = new Musique();
            $musique->setIdMusique($data["idMusique"]);
            $musique->setTitre($data["titre"]);
            $musique->setUrlMusique($data["fileMusique"]);
            $musique->setDate(new DateTime($data["date"]));
            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($data["idUtilisateur"]);
            $utilisateur->setLogin($data["login"]);
            $utilisateur->setProfilePictureName($data["profilePictureName"]);
            $musique->setUtilisateur($utilisateur);
            $musiques[] = $musique;
        }

        return $musiques;
    }
}