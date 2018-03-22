<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tache", type="string", length=255)
     */
    private $tache;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="task")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;

    public function __construct($customer)
    {
        $this->setCustomer($customer);
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
}
