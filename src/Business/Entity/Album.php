<?php

namespace TheFeed\Business\Entity;

class Album
{
    /**
     * @var int
     */
    private $idAlbum;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $urlImage;

    /**
     * @var Utilisateur
     */
    private $utilisateur;

    public static function create($nom, $urlImage, $utilisateur)
    {
        $album = new Album();
        $album->nom = $nom;
        $album->urlImage = $urlImage;
        $album->utilisateur = $utilisateur;
        return $album;
    }

    /**
     * @return int
     */
    public function getIdAlbum(): int
    {
        return $this->idAlbum;
    }

    /**
     * @param int $idAlbum
     */
    public function setIdAlbum(int $idAlbum): void
    {
        $this->idAlbum = $idAlbum;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getUrlImage(): string
    {
        return $this->urlImage;
    }

    /**
     * @param string $urlImage
     */
    public function setUrlImage(string $urlImage): void
    {
        $this->urlImage = $urlImage;
    }

    /**
     * @return Utilisateur
     */
    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    /**
     * @param Utilisateur $utilisateur
     */
    public function setUtilisateur(Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

}