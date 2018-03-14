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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller
{
    /**
     * @Route("/simply", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need

        $count = $this->getDoctrine()->getRepository(Customer::class)->nbreClient();

        return $this->render('home.html.twig', array ( 'nbreClient' => $count));
    }

    /**
     * @Route("/simply/add/client/{id}", name="addCustomer" , requirements={"page"="\d+"})
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

             $entityManager = $this->getDoctrine()->getManager;
             $entityManager->persist($customer);
             $entityManager->flush();

            return $this->redirectToRoute(
               'Customer'
            );
        }

        return $this->render('addCustomer.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/simply/list/client/{page}", name="Customer" , requirements={"page"="\d+"} , defaults={"page": 1},)
     */
    public function customerAction($page){


       //GEstion de la pagination

        //TODO Query builder pour connaitre le nombre exact avec un query builder
        $nbreCustomer = 123;
        $nbreParPage = 10;

        $nbrePagePagination = ceil($nbreCustomer/$nbreParPage);

        $page =  (($page == 0 ) ? 1 : $page);
        $page =  (($page > $nbrePagePagination ) ? $nbrePagePagination : $page);

        $offset = ($page-1)*$nbreParPage;

        //GEstion pagination Fin


       $customer_liste = $this->getDoctrine()->getRepository(Customer::class)->pagination($nbreParPage,$offset);



        return $this->render('customer.html.twig' ,array(
                'customer_liste' => $customer_liste,
                'NbreDePage' => $nbrePagePagination,


        ));

    }

    /**
     * @Route("/simply/client/{id}", name="detailCustomer" , requirements={"id"="\d+"})
     */
    public function detailCustomerAction($id){

        $customer = $this->getDoctrine()->getRepository(Customer::class)->findBy(array('id' => $id));


        return $this->render('detailCustomer.html.twig', array('customer' => $customer));

    }

    /**
     * @Route("/simply/delete/client/{id}", name="deleteCustomer" , requirements={"id"="\d+"})
     */
    public function deleteCustomerAction($id){


        //TODO mettre une alerte pour l'utilisaaateur // message = suppresion est définitive

        $entityManager = $this->getDoctrine()->getManager();
        $customer = $entityManager->getRepository(Customer::class)->find($id);

        if (!$customer) {
            throw $this->createNotFoundException(
                'No customer found for id '.$id
            );
        }

        $entityManager->remove($customer);
        $entityManager->flush();

        return $this->redirectToRoute('Customer');
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

}
