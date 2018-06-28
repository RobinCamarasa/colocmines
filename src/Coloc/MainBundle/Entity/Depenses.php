<?php

namespace Coloc\MainBundle\Entity;

/**
 * Depenses
 */
class Depenses
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var \DateTime
     */
    private $date;

    private $montant;

    /**
     * @return mixed
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param mixed $montant
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    }

    /**
     * @var float
     */
    private $nbPartTotal;

    private $nbPartTotalColoc;

    /**
     * @return mixed
     */
    public function getNbPartTotalColoc()
    {
        return $this->nbPartTotalColoc;
    }

    /**
     * @param mixed $nbPartTotalColoc
     */
    public function setNbPartTotalColoc($nbPartTotalColoc)
    {
        $this->nbPartTotalColoc = $nbPartTotalColoc;
    }


    /**
     * @var float
     */
    private $nbPartRobin;

    /**
     * @var float
     */
    private $nbPartEva;

    /**
     * @var float
     */
    private $nbPartSylvain;

    private $nbPartAutres;

    /**
     * @return mixed
     */
    public function getNbPartAutres()
    {
        return $this->nbPartAutres;
    }

    /**
     * @param mixed $nbPartAutres
     */
    public function setNbPartAutres($nbPartAutres)
    {
        $this->nbPartAutres = $nbPartAutres;
    }

    private $paye_par;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPayePar()
    {
        return $this->paye_par;
    }

    /**
     * @param mixed $paye_par
     */
    public function setPayePar($paye_par)
    {
        $this->paye_par = $paye_par;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Depenses
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Depenses
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set nbPartTotal
     *
     * @param float $nbPartTotal
     *
     * @return Depenses
     */
    public function setNbPartTotal($nbPartTotal)
    {
        $this->nbPartTotal = $nbPartTotal;

        return $this;
    }

    /**
     * Get nbPartTotal
     *
     * @return float
     */
    public function getNbPartTotal()
    {
        return $this->nbPartTotal;
    }

    /**
     * Set nbPartRobin
     *
     * @param float $nbPartRobin
     *
     * @return Depenses
     */
    public function setNbPartRobin($nbPartRobin)
    {
        $this->nbPartRobin = $nbPartRobin;

        return $this;
    }

    /**
     * Get nbPartRobin
     *
     * @return float
     */
    public function getNbPartRobin()
    {
        return $this->nbPartRobin;
    }

    /**
     * Set nbPartEva
     *
     * @param float $nbPartEva
     *
     * @return Depenses
     */
    public function setNbPartEva($nbPartEva)
    {
        $this->nbPartEva = $nbPartEva;

        return $this;
    }

    /**
     * Get nbPartEva
     *
     * @return float
     */
    public function getNbPartEva()
    {
        return $this->nbPartEva;
    }

    /**
     * Set nbPartSylvain
     *
     * @param float $nbPartSylvain
     *
     * @return Depenses
     */
    public function setNbPartSylvain($nbPartSylvain)
    {
        $this->nbPartSylvain = $nbPartSylvain;

        return $this;
    }

    /**
     * Get nbPartSylvain
     *
     * @return float
     */
    public function getNbPartSylvain()
    {
        return $this->nbPartSylvain;
    }
}

