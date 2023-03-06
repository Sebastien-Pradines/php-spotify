<?php

namespace TheFeed\Business\Entity;

use DateTime;

class Musique
{
    /**
     * @var int
     */
    private $idMusique;

    /**
     * @var string
     */
    private $titre;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $urlMusique;

    /**
     * @var Utilisateur
     */
    private $utilisateur;

    public static function create($url, $titre, $utilisateur)
    {
        $musique = new Musique();
        $musique->titre = $titre;
        $musique->urlMusique = $url;
        $musique->date = new DateTime();
        $musique->utilisateur = $utilisateur;
        return $musique;
    }

    /**
     * @return int
     */
    public function getIdMusique(): int
    {
        return $this->idMusique;
    }

    /**
     * @param int $idMusique
     */
    public function setIdMusique(int $idMusique): void
    {
        $this->idMusique = $idMusique;
    }

    /**
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getUrlMusique(): string
    {
        return $this->urlMusique;
    }

    /**
     * @param string $urlMusique
     */
    public function setUrlMusique(string $urlMusique): void
    {
        $this->urlMusique = $urlMusique;
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