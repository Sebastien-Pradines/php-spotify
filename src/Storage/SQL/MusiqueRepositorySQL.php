<?php

namespace TheFeed\Storage\SQL;

use DateTime;
use Framework\Storage\Repository;
use PDO;
use Symfony\Component\DependencyInjection\Tests\A;
use TheFeed\Business\Entity\Album;
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
        $values = [
            "idMusique" => $id,
        ];
        $statement = $this->pdo->prepare("SELECT m.idMusique, titre, date, idUtilisateur, login, fileMusique, profilePictureName, a.idAlbum, a.nom, a.nomImage
                                                FROM musiques m
                                                JOIN utilisateurs u on m.idAuteur = u.idUtilisateur
                                                LEFT OUTER JOIN albums_musique am on m.idMusique = am.idMusique
                                                LEFT OUTER JOIN albums a on am.idAlbum = a.idAlbum
                                                WHERE m.idMusique = :idMusique");
        $statement->execute($values);
        $data = $statement->fetch();
        if($data) {
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
            if($data["idAlbum"]){
                $album = new Album();
                $album->setNom($data["nom"]);
                $album->setIdAlbum($data["idAlbum"]);
                $album->setUrlImage($data["nomImage"]);
                $album->setUtilisateur($utilisateur);
                $musique->setAlbum($album);
            }
            return $musique;
        }
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

    public function remove($musique)
    {
        $values = [
            "idMusique" => $musique->getIdMusique(),
        ];
        $statement = $this->pdo->prepare("DELETE FROM musiques WHERE idMusique = :idMusique");
        $statement->execute($values);
    }

    public function getAllFrom($idUtilisateur) : array {
        $values = [
            "idAuteur" => $idUtilisateur,
        ];
        $statement = $this->pdo->prepare("SELECT m.idMusique, titre, date, fileMusique, idUtilisateur, login, profilePictureName, a.idAlbum, a.nom, a.nomImage
                                                FROM musiques m 
                                                JOIN utilisateurs u on m.idAuteur = u.idUtilisateur
                                                LEFT OUTER JOIN albums_musique am on m.idMusique = am.idMusique
                                                LEFT OUTER JOIN albums a on am.idAlbum = a.idAlbum
                                                WHERE m.idAuteur = :idAuteur                    
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
            if($data["idAlbum"]){
                $album = new Album();
                $album->setNom($data["nom"]);
                $album->setIdAlbum($data["idAlbum"]);
                $album->setUrlImage($data["nomImage"]);
                $album->setUtilisateur($utilisateur);
                $musique->setAlbum($album);
            }
            $musiques[] = $musique;
        }

        return $musiques;
    }

    public function getAllFromAlbum($idAlbum) : array {
        $values = [
            "idAlbum" => $idAlbum,
        ];
        $statement = $this->pdo->prepare("SELECT m.idMusique, titre, date, fileMusique, idUtilisateur, login, profilePictureName, a.idAlbum, a.nom, a.nomImage
                                                FROM musiques m 
                                                JOIN utilisateurs u on m.idAuteur = u.idUtilisateur
                                                LEFT OUTER JOIN albums_musique am on m.idMusique = am.idMusique
                                                LEFT OUTER JOIN albums a on am.idAlbum = a.idAlbum
                                                WHERE a.idAlbum = :idAlbum                    
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
            if($data["idAlbum"]){
                $album = new Album();
                $album->setNom($data["nom"]);
                $album->setIdAlbum($data["idAlbum"]);
                $album->setUrlImage($data["nomImage"]);
                $album->setUtilisateur($utilisateur);
                $musique->setAlbum($album);
            }
            $musiques[] = $musique;
        }

        return $musiques;
    }

    public function setAlbum($idAlbum, $idMusique){
        $values = [
            "idAlbum" => $idAlbum,
            "idMusique" => $idMusique,
        ];

        $statement = $this->pdo->prepare("INSERT INTO albums_musique (idAlbum, idMusique) VALUES(:idAlbum, :idMusique);");
        $statement->execute($values);
    }
}