<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Customer;
use AppBundle\Repository\CustomerRepository;

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
     * @Route("/add/client/{id}", name="addCustomer" , requirements={"page"="\d+"})
     */
    public function addCustomerAction(Request $request,$id = null){

        $customer = new Customer();


        //Si l'id existe, on charge le client dans l'objet
        if (!is_null($id)){

            $repository = $this->getDoctrine()->getRepository(Customer::class);
            $customer = $repository->find($id);


        }



        $form = $this->createFormBuilder($customer)
            ->add('nom')
            ->add('prenom')
            ->add('societe')
            ->add('adresse')
            ->add('numfixe')
            ->add('numport')
            ->add('mail')
            ->add('Enregistrer', SubmitType::class, array('label' => 'Enregistrer'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $customer = $form->getData();

             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($customer);
             $entityManager->flush();


        }

        return $this->render('addCustomer.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/list/client/{page}", name="Customer" , requirements={"page"="\d+"} , defaults={"page": 1},)
     */
    public function customerAction($page){


       //GEstion de la pagination

        //TODO Query builder pour connaitre le nombre exact avec un query builder
        $nbreCustomer = 123;
        $nbreParPage = 10;

        $nbrePage = ceil($nbreCustomer/$nbreParPage);

        $page =  (($page == 0 ) ? 1 : $page);
        $page =  (($page > $nbrePage ) ? $nbrePage : $page);

        $offset = ($page-1)*$nbreParPage;

        //GEstion pagination Fin


       $customer_liste = $this->getDoctrine()->getRepository(Customer::class)->pagination($nbrePage,$offset);



        return $this->render('customer.html.twig' ,array(
                'customer_liste' => $customer_liste,
                'NbreDePage' => $nbrePage,


        ));

    }

    /**
     * @Route("/client/{id}", name="detailCustomer" , requirements={"id"="\d+"})
     */
    public function detailCustomerAction($id){

        $customer = $this->getDoctrine()->getRepository(Customer::class)->findBy(array('id' => $id));


        return $this->render('detailCustomer.html.twig', array('customer' => $customer));

    }
}
