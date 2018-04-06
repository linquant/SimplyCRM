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

use AppBundle\Entity\Task;
use AppBundle\Repository\TaskRepository;


use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller
{
    /**
     * @Route("/simply", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('login');
        }

        $doctrine = $this->getDoctrine();

        $countCustomer = $doctrine->getRepository(Customer::class)->nbreClient($this->getUser()->getId());
        $countTask =  $doctrine->getRepository(Task::class)->countTaskByUser($this->getUser()->getId());
        $countTaskByEtat = $doctrine->getRepository(Task::class)->countTaskByUserAndEtat($this->getUser()->getId(), $this->getParameter('tache_etat'));




        return $this->render(
            'home.html.twig',
            array( 'nbreClient' => $countCustomer,
                    'nbreTask' => $countTask,
                    'nbreTaskEtat' => $countTaskByEtat  ,
        )
        );
    }

    /**
     * @Route("/simply/add/client/{id}", name="addCustomer" , requirements={"page"="\d+"})
     */
    public function addCustomerAction(Request $request, $id = null)
    {
        $customer = new Customer();


        //Si l'id existe, on charge le client dans l'objet
        if (!is_null($id)) {
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
            $customer->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('detailCustomer', array('id' => $customer->getId()));
        }

        return $this->render('addCustomer.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/simply/list/client/{page}", name="Customer" , requirements={"page"="\d+"} , defaults={"page": 1},)
     */
    public function customerAction($page)
    {


       //GEstion de la pagination


        $nbreCustomer = $this->getDoctrine()->getRepository(Customer::class)->nbreClient($this->getUser()->getId());
        $nbreParPage = $this->getParameter('pagination_nbre_item');

        $nbrePagePagination = ceil($nbreCustomer/$nbreParPage);

        $page =  (($page == 0) ? 1 : $page);
        $page =  (($page > $nbrePagePagination) ? $nbrePagePagination : $page);

        $offset = ($page-1)*$nbreParPage;
        $offset =  (($offset == -10) ? 10 : $offset);

        //GEstion pagination Fin


        $customer_liste = $this->getDoctrine()->getRepository(Customer::class)->pagination($nbreParPage, $offset, $this->getUser()->getId());




        return $this->render('customer.html.twig', array(
                'customer_liste' => $customer_liste,
                'NbreDePage' => $nbrePagePagination,


        ));
    }

    /**
     * @Route("/simply/client/{id}", name="detailCustomer" , requirements={"id"="\d+"})
     */
    public function detailCustomerAction($id)
    {
        $doctrine = $this->getDoctrine();
        
        $customer = $doctrine->getRepository(Customer::class)->findBy(array('id' => $id));
        
        $tasks= $doctrine->getRepository(Task::class)->listByCustomer($id);
      
        
        return $this->render('detailCustomer.html.twig', array(
                                                                'customer' => $customer,
                                                                'taches' => $tasks,

                                                            ));
    }

    /**
     * @Route("/simply/delete/client/{id}", name="deleteCustomer" , requirements={"id"="\d+"})
     */
    public function deleteCustomerAction($id)
    {


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
     * @Route("/simply/export/", name="exportCustomer" )
     */
    public function export()
    {

        //controler que le user est logué
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('login');
        }
        //réucpération des données en base avec l'id user

        $listeCustomer = $this->getDoctrine()->getRepository(Customer::class)->listeCustomer($this->getUser());

        //Création d'un fichier temporaire'
        $uniqname = uniqid(rand(), true).'.csv';
        $temp_file = fopen($_SERVER["DOCUMENT_ROOT"]."/export/".$uniqname, "a");

        // Insertion des données dans le fichier
        $data = null ;
        foreach ($listeCustomer as $index => $item) {
            $data = null ;

            $data .= $item->getnom().',';

            $data .= addcslashes($item->getprenom(), "\n\r").',';
            $data .= addcslashes($item->getsociete(), "\n\r").',';
            $data .= addcslashes($item->getadresse(), "\n\r").',';
            $data .= addcslashes($item->getnumfixe(), "\n\r").',';
            $data .= addcslashes($item->getnumport(), "\n\r").',';
            $data .= addcslashes($item->getmail(), "\n\r")."\n";

            fwrite($temp_file, $data);
        }
        //génération de l'URL de téléchargement
        $lien ="/export/".$uniqname;

        //fermeture du fichiers
        fclose($temp_file);

        //Supprime tous les fichiers de plus d'une heure.
        $this->deleteOldCSV('export');

        return $this->render('export.html.twig', array( 'lien' => $lien));
    }

    /**
     *  Supprime tous les Fichiers de plus d'une heure // Cron task du pauvre :)
     * @param $directory
     * @return string
     */
    private function deleteOldCSV($directory)
    {
        $date = new \DateTime();

//        Parcours tous les fichiers du répertoire
        $handler = opendir($directory);
        while ($file = readdir($handler)) {
            if ($file != '.' && $file != '..' && $file != "robots.txt" && $file != ".htaccess") {
                $currentModified = filectime($directory."/".$file);

//                Si la date est inférieur au timestamp - 1 h on supprimer le fichiers
                if ($currentModified < $date->getTimestamp() - 3600) {
                    unlink($directory."/".$file);
                }
            }
        }
        closedir($handler);
    }
}
