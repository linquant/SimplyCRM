<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Task controller.
 *
 * @Route("task")
 */
class TaskController extends Controller
{
    /**
     * Lists all task entities.
     *
     * @Route("/list/{etat}", name="task_index")
     * @Method("GET")
     */
    public function indexAction($etat)
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $this->redirectToRoute('login');
        }


        $em = $this->getDoctrine()->getManager();


        $tasks = $em->getRepository('AppBundle:Task')->TaskDate($this->getUser()->getId(), $etat);



        return $this->render('task/index.html.twig', array(
            'tasks' => $tasks,
            'etats' => $this->getParameter('tache_etat'),
        ));
    }

    /**
     * Creates a new task entity.
     *
     * @Route("/new/{id_customer}", name="task_new", requirements={"page"="\d+"})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id_customer)
    {
        $customer = $this->getDoctrine()->getRepository(Customer::class)->find($id_customer);

        //TODO Vérifier que le user est bien propriétaire du customer

        $task = new Task($customer, $this->getUser());

        $form = $this->createForm('AppBundle\Form\TaskType', $task, array(
            'tache_etat' => $this->getParameter('tache_etat'),
        ));


        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('detailCustomer', array('id' => $id_customer));
        }

        return $this->render('task/new.html.twig', array(
            'task' => $task,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a task entity.
     *
     * @Route("/{id}", name="task_show")
     * @Method("GET")
     */
    public function showAction(Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);

        return $this->render('task/show.html.twig', array(
            'task' => $task,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing task entity.
     *
     * @Route("/{id}/edit/", name="task_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Task $task)
    {
        $deleteForm = $this->createDeleteForm($task);
        $editForm = $this->createForm('AppBundle\Form\TaskType', $task, array(
            'tache_etat' => $this->getParameter('tache_etat'),
        ));
        $editForm->handleRequest($request);


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('detailCustomer', array('id' => $task->getCustomer()->getId()));
        }


        return $this->render('task/edit.html.twig', array(
            'task' => $task,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'customer'=> $task->getCustomer()->getId(),
        ));
    }

    /**
     * Deletes a task entity.
     *
     * @Route("/{id}", name="task_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Task $task)
    {
        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
        }


        //redirige vers le customer détail avec en Paramétre son ID
        return $this->redirectToRoute('detailCustomer', array('id' => $task->getCustomer()->getID()));
    }

    /**
     * Creates a form to delete a task entity.
     *
     * @param Task $task The task entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', array('id' => $task->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
