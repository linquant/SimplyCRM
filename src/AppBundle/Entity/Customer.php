<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;



    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="societe", type="string", length=255, nullable=true)
     */
    private $societe;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="text", nullable=true)
     */
    private $adresse;

    /**
     * @var int
     *
     * @ORM\Column(name="numfixe", type="integer", nullable=true)
     */
    private $numfixe;

    /**
     * @var int
     *
     * @ORM\Column(name="numport", type="integer", nullable=true)
     */
    private $numport;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

        /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="customer")
     * @ORM\JoinColumn(nullable=true)
     */

    private $user;    

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Customer
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Customer
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set societe
     *
     * @param string $societe
     *
     * @return Customer
     */
    public function setSociete($societe)
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * Get societe
     *
     * @return string
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Customer
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set numfixe
     *
     * @param integer $numfixe
     *
     * @return Customer
     */
    public function setNumfixe($numfixe)
    {
        $this->numfixe = $numfixe;

        return $this;
    }

    /**
     * Get numfixe
     *
     * @return int
     */
    public function getNumfixe()
    {
        return $this->numfixe;
    }

    /**
     * Set numport
     *
     * @param integer $numport
     *
     * @return Customer
     */
    public function setNumport($numport)
    {
        $this->numport = $numport;

        return $this;
    }

    /**
     * Get numport
     *
     * @return int
     */
    public function getNumport()
    {
        return $this->numport;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Customer
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }
}

