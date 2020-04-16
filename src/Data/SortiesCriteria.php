<?php
namespace App\Data;



class SortiesCriteria {
    /**
     * @var int
     */
    public $site = null;

    /**
     * @var string
     */
    public $search = "";

    /**
     * @var \DateTime
     */
    public $dateDebut = null;

    /**
     * @var \DateTime
     */
    public $dateFin = null;

    /**
     * @var boolean
     */
    public $organisateur = false;

    /**
     * @var boolean
     */
    public $inscrit = false;

    /**
     * @var boolean
     */
    public $pasInscrit = false;

    /**
     * @var boolean
     */
    public $sortiePassee = false;


    /**
     * @return int
     */
    public function getSite(): ?int {
        return $this->site;
    }

    /**
     * @param int
     */
    public function setSite(int $site): void {
        $this->site = $site;
    }

    /**
     * @return string
     */
    public function getSearch(): ?string {
        return $this->search;
    }

    /**
     * @param string $search
     */
    public function setSearch(string $search): void {
        $this->search = $search;
    }

    /**
     * @return \DateTime
     */
    public function getDateDebut(): ?\DateTime {
        return $this->dateDebut;
    }

    /**
     * @param \DateTime $dateDebut
     */
    public function setDateDebut(\DateTime $dateDebut): void {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return \DateTime
     */
    public function getDateFin(): ?\DateTime {
        return $this->dateFin;
    }

    /**
     * @param \DateTime $dateFin
     */
    public function setDateFin(\DateTime $dateFin): void {
        $this->dateFin = $dateFin;
    }

    /**
     * @return bool
     */
    public function isOrganisateur(): bool {
        return $this->organisateur;
    }

    /**
     * @param bool $organisateur
     */
    public function setOrganisateur(bool $organisateur): void {
        $this->organisateur = $organisateur;
    }

    /**
     * @return bool
     */
    public function isInscrit(): bool {
        return $this->inscrit;
    }

    /**
     * @param bool $inscrit
     */
    public function setInscrit(bool $inscrit): void {
        $this->inscrit = $inscrit;
    }

    /**
     * @return bool
     */
    public function isPasInscrit(): bool {
        return $this->pasInscrit;
    }

    /**
     * @param bool $pasInscrit
     */
    public function setPasInscrit(bool $pasInscrit): void {
        $this->pasInscrit = $pasInscrit;
    }

    /**
     * @return bool
     */
    public function isSortiePassee(): bool {
        return $this->sortiePassee;
    }

    /**
     * @param bool $sortiePassee
     */
    public function setSortiePassee(bool $sortiePassee): void {
        $this->sortiePassee = $sortiePassee;
    }


}