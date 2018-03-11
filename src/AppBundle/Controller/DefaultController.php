<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Customer;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/add/client", name="addCustomer")
     */
    public function addCustomerAction(){

        return $this->render('addCustomer.html.twig');
    }

    /**
     * @Route("/client", name="Customer")
     */
    public function customerAction(){

        $customer_liste = $this->getDoctrine()->getRepository(Customer::class)->findall();


        return $this->render('customer.html.twig' ,array('customer_liste' => $customer_liste));

    }
}
