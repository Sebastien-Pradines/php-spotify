<?php

namespace TheFeed\Business\Services;

use TheFeed\Business\Entity\Musique;
use TheFeed\Business\Exception\ServiceException;

class MusiqueService
{
    private $repository;

    private UtilisateurService $serviceUtilisateur;

    private $musiqueRoot;

    public function __construct($repositoryManager, $serviceUtilisateur, $musiqueRoot)
    {
        $this->repository = $repositoryManager->getRepository(Musique::class);
        $this->serviceUtilisateur = $serviceUtilisateur;
        $this->musiqueRoot = $musiqueRoot;
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
}