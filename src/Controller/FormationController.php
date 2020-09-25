<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/formation")
 */
class FormationController extends AbstractController
{
    /**
     * @Route("/", name="sessions")
     */
    public function index()
    {
        $sessions = $this->getDoctrine()
                        ->getRepository(Session::class)
                        ->findAll();
        return $this->render('formation/index.html.twig', [
            "sessions"=>$sessions
        ]);
    }

    /**
     * @Route("/new", name="new_session")
     * @Route("/edit/{id}", name="edit_session")
     */
    public function newFormation(Request $request, Session $session = null){
        $manager = $this->getDoctrine()->getManager();
        $edit = false;
        
        if ($session) {
            $edit = true;
        }else $session = new Session();
        

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$edit) {
                $session->setUser($this->getUser());
            }
            
            $manager->persist($session);
            $manager->flush();

            return $this->redirectToRoute("sessions");
        }

        return $this->render("formation/session_form.html.twig", [
            "form"=>$form->createView(),
            "edit"=>$edit
        ]);
    }

    /**
     * @Route("/{id}", name="session")
     */
    public function detailSession(Session $session){
        return $this->render("formation/detail_session.html.twig", [
            "session"=>$session
        ]);
    }
}
