<?php

namespace TheFeed\Business\Services;

use TheFeed\Business\Entity\Album;
use TheFeed\Business\Entity\Musique;
use TheFeed\Business\Exception\ServiceException;

class AlbumService
{
    private $repository;

    private UtilisateurService $serviceUtilisateur;

    private $albumRoot;

    public function __construct($repositoryManager, $serviceUtilisateur, $albumRoot)
    {
        $this->repository = $repositoryManager->getRepository(Album::class);
        $this->serviceUtilisateur = $serviceUtilisateur;
        $this->albumRoot = $albumRoot;
    }
    public function createAlbum($nom, $albumImg, $userId){
        if($nom == null || $nom == "") {
            throw new ServiceException("Le nom ne peut pas être vide!");
        }
        if(strlen($nom) > 80) {
            throw new ServiceException("Le nom ne peut pas dépasser 80 caractères!");
        }
        $utilisateur = $this->serviceUtilisateur->getUtilisateur($userId, false);
        $fileExtension = $albumImg->guessExtension();
        if(!in_array($fileExtension, ['png', 'jpg', 'jpeg'])) {
            throw new ServiceException("La photo de l'album n'est pas au bon format!");
        }

        $albumName = uniqid().'.'.$fileExtension;
        $albumImg->move($this->albumRoot, $albumName);
        $album = Album::create($albumName, $nom, $utilisateur);
        $id = $this->repository->create($album);
        return $this->repository->get($id);
    }

    public function getAlbumFrom($refUtilisateur) {
        $utilisateur = $this->serviceUtilisateur->getUtilisateur($refUtilisateur);
        if($utilisateur == null) {
            $utilisateur = $this->serviceUtilisateur->getUtilisateurByLogin($refUtilisateur, false);
        }
        return $this->repository->getAllFrom($utilisateur->getIdUtilisateur());
    }

}