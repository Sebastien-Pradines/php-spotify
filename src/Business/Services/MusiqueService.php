<?php

namespace TheFeed\Business\Services;

use TheFeed\Business\Entity\Musique;
use TheFeed\Business\Exception\ServiceException;

class MusiqueService
{
    private $repository;

    private UtilisateurService $serviceUtilisateur;

    private AlbumService $serviceAlbum;

    private $musiqueRoot;

    public function __construct($repositoryManager, $serviceUtilisateur, $serviceAlbum, $musiqueRoot)
    {
        $this->repository = $repositoryManager->getRepository(Musique::class);
        $this->serviceUtilisateur = $serviceUtilisateur;
        $this->serviceAlbum = $serviceAlbum;
        $this->musiqueRoot = $musiqueRoot;
    }

    public function getMusique($idMusique, $allowNull = true) {
        $musique =  $this->repository->get($idMusique);
        if(!$allowNull && $musique == null) {
            throw new ServiceException("Musique inexistante!");
        }
        return $musique;
    }

    public function createMusique($titre, $musiqueFile, $userId){
        if($titre == null || $titre == "") {
            throw new ServiceException("Le titre ne peut pas être vide!");
        }
        if(strlen($titre) > 80) {
            throw new ServiceException("Le titre ne peut pas dépasser 80 caractères!");
        }
        $utilisateur = $this->serviceUtilisateur->getUtilisateur($userId, false);
        $fileExtension = $musiqueFile->guessExtension();
        if(!in_array($fileExtension, ['mp3', 'mp3', 'mp3'])) {
            throw new ServiceException("Le fichier n'est pas au bon format!");
        }

        $musiqueName = uniqid().'.'.$fileExtension;
        $musiqueFile->move($this->musiqueRoot, $musiqueName);
        $musique = Musique::create($musiqueName, $titre, $utilisateur);
        $id = $this->repository->create($musique);
        return $this->repository->get($id);
    }

    public function getMusiquesFrom($refUtilisateur) {
        $utilisateur = $this->serviceUtilisateur->getUtilisateur($refUtilisateur);
        if($utilisateur == null) {
            $utilisateur = $this->serviceUtilisateur->getUtilisateurByLogin($refUtilisateur, false);
        }
        return $this->repository->getAllFrom($utilisateur->getIdUtilisateur());
    }

    public function getMusiquesFromAlbum($idAlbum) {
        return $this->repository->getAllFromAlbum($idAlbum);
    }

    public function setAlbum($idAlbum, $idMusique){
        $musique = $this->getMusique($idMusique);
        $album = $this->serviceAlbum->getAlbum($idAlbum);
        $musique->setAlbum($album);
        $this->repository->setAlbum($album->getIdAlbum(), $idMusique);
    }

    public function removeMusique($idUtilisateur, $idMusique) {
        $utilisateur = $this->serviceUtilisateur->getUtilisateur($idUtilisateur, false);
        $musique = $this->getMusique($idMusique, false);
        if($utilisateur->getIdUtilisateur() != $musique->getUtilisateur()->getIdUtilisateur()) {
            throw new ServiceException("L'utilisateur n'est pas l'auteur de cette musique!");
        }
        $this->repository->remove($musique);
    }
}