<?php

namespace TheFeed\Storage\SQL;

use Framework\Storage\Repository;
use PDO;
use TheFeed\Business\Entity\Album;
use TheFeed\Business\Entity\Musique;
use TheFeed\Business\Entity\Utilisateur;

class AlbumRepositorySQL implements Repository
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

    public function create($album)
    {
        $values = [
            "nom" => $album->getNom(),
            "nomImage" => $album->getUrlImage(),
            "idAuteur" => $album->getUtilisateur()->getIdUtilisateur()
        ];
        $statement = $this->pdo->prepare("INSERT INTO albums (nom, nomImage, idAuteur) VALUES(:nom, :nomImage, :idAuteur);");
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
        $statement = $this->pdo->prepare("SELECT idAlbum, nom, nomImage, idUtilisateur, login, profilePictureName 
                                                FROM album a 
                                                JOIN utilisateurs u on a.idAuteur = u.idUtilisateur
                                                WHERE idAuteur = :idAuteur                    
                                                ORDER BY date DESC");
        $statement->execute($values);

        $albums = [];

        foreach ($statement as $data) {
            $album = new Album();
            $album->setIdAlbum($data["idAlbum"]);
            $album->setNom($data["nom"]);
            $album->setUrlImage($data["nomImage"]);
            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($data["idUtilisateur"]);
            $utilisateur->setLogin($data["login"]);
            $utilisateur->setProfilePictureName($data["profilePictureName"]);
            $album->setUtilisateur($utilisateur);
            $albums[] = $album;
        }

        return $albums;
    }
}