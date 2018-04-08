<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User as User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 */
class Task
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     *
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tache", type="string", length=255)
     *
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "be at least {{ limit }} characters long",
     *      maxMessage = " be longer than {{ limit }} characters"
     * )
     */
    private $tache;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="task")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;


    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="task")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreation;

    /**
     * @ORM\Column(type="datetime")
     *
     *
     */
    private $echeance;

    /**
     * @ORM\Column(type="string" , length=255)
     *
     * @assert\length( max = 255)
     */
    private $etat;
    public function __construct($customer, $user)
    {
        $this->setCustomer($customer);
        $this->setDatecreation(new \Datetime());
        $this->setEcheance(new \Datetime());
        $this->setUser($user);
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tache.
     *
     * @param string $tache
     *
     * @return Task
     */
    public function setTache($tache)
    {
        $this->tache = $tache;

        return $this;
    }

    /**
     * Get tache.
     *
     * @return string
     */
    public function getTache()
    {
        return $this->tache;
    }

    /**
     * @return mixed
     */
    public function getDatecreation()
    {
        return $this->datecreation;
    }

    /**
     * @param mixed $datecreation
     */
    public function setDatecreation($datecreation)
    {
        $this->datecreation = $datecreation;
    }

    /**
     * @return mixed
     */
    public function getEcheance()
    {
        return $this->echeance;
    }

    /**
     * @param mixed $echeance
     */
    public function setEcheance($echeance)
    {
        $this->echeance = $echeance;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }


    /**
     * Set customer.
     *
     * @param \stdClass $customer
     *
     * @return Task
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer.
     *
     * @return \stdClass
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return mixed
     */


    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
