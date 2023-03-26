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
        $values = [
            "idAlbum" => $id,
        ];
        $statement = $this->pdo->prepare("SELECT idAlbum, nom, nomImage, u.idUtilisateur, login, profilePictureName 
                                                FROM albums a
                                                JOIN utilisateurs u on a.idAuteur = u.idUtilisateur
                                                WHERE idAlbum = :idAlbum");

        $statement->execute($values);
        $data = $statement->fetch();

        $album = new Album();
        $album->setIdAlbum($data["idAlbum"]);
        $album->setNom($data["nom"]);
        $album->setUrlImage($data["nomImage"]);
        $utilisateur = new Utilisateur();
        $utilisateur->setIdUtilisateur($data["idUtilisateur"]);
        $utilisateur->setLogin($data["login"]);
        $utilisateur->setProfilePictureName($data["profilePictureName"]);
        $album->setUtilisateur($utilisateur);

        return $album;
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

    public function remove($album)
    {
        $values = [
            "idAlbum" => $album->getIdAlbum(),
        ];
        $statement = $this->pdo->prepare("DELETE FROM albums WHERE idAlbum = :idAlbum");
        $statement->execute($values);
    }

    public function getAllFrom($idUtilisateur) : array {
        $values = [
            "idAuteur" => $idUtilisateur,
        ];
        $statement = $this->pdo->prepare("SELECT idAlbum, nom, nomImage, idUtilisateur, login, profilePictureName 
                                                FROM albums a 
                                                JOIN utilisateurs u on a.idAuteur = u.idUtilisateur
                                                WHERE idAuteur = :idAuteur                    
                                                ORDER BY idAlbum DESC");
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

    public function getFromMusique($id)
    {
        $values = [
            "idMusique" => $id,
        ];
        $statement = $this->pdo->prepare("SELECT a.idAlbum, nom, nomImage, u.idUtilisateur, login, profilePictureName 
                                                FROM albums a
                                                JOIN utilisateurs u on a.idAuteur = u.idUtilisateur
                                                JOIN albums_musique am on a.idAlbum = am.idAlbum
                                                WHERE am.idMusique = :idMusique");

        $statement->execute($values);
        $data = $statement->fetch();

        if($data){
            $album = new Album();
            $album->setIdAlbum($data["idAlbum"]);
            $album->setNom($data["nom"]);
            $album->setUrlImage($data["nomImage"]);
            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($data["idUtilisateur"]);
            $utilisateur->setLogin($data["login"]);
            $utilisateur->setProfilePictureName($data["profilePictureName"]);
            $album->setUtilisateur($utilisateur);
            return $album;
        }
    }
}