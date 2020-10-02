<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Form\ProgrammeType;
use App\Form\InscriptionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @Route("/formation")
 * @IsGranted("ROLE_USER")
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

            return $this->redirectToRoute("session", ["id"=>$session->getId()]);
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

    /**
     * @Route("/stagiaires/{id}", name="stagiaires_in_session")
     */
    public function addStagiaire(Request $request, Session $session){
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(InscriptionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $stagiaires = $this->getDoctrine()
                            ->getRepository(Stagiaire::class)
                            ->findFromSession($session);
            foreach ($stagiaires as $stg) {
                $stg->removeInscription($session);
            }

            foreach ($session->getStagiaires() as $stagiaire) {
                $session->addStagiaire($stagiaire);
            }
            
            $manager->persist($session);
            $manager->flush();

            
            return $this->redirectToRoute("session", ["id"=>$session->getId()]);
        }

        return $this->render("formation/inscription_stagiaires.html.twig", [
            "session"=>$session,
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/modules/{id}", name="session_programme")
     */
    public function newProgramme(Request $request, Session $session){
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProgrammeType::class, $session);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $programmes = $this->getDoctrine()
                            ->getRepository(Programme::class)
                            ->findBy(["session"=>$session->getId()]);
            foreach ($programmes as $prgrm) {
                $manager->remove($prgrm);
            }

            foreach ($session->getProgrammes() as $prgrm) {
                $prgrm->setUser($this->getUser());
                $prgrm->setSession($session);
                $manager->persist($prgrm);
            }
            $manager->flush();
            

            return $this->redirectToRoute("session", ["id"=>$session->getId()]);
        }

        return $this->render("formation/module_add.html.twig",[
            "session"=>$session,
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_session")
     */
    public function deleteSession(Session $session){
        $manager = $this->getDoctrine()->getManager();
        foreach ($session->getStagiaires() as $stagiaire) {
            $session->removeStagiaire($stagiaire);
        }
        foreach ($session->getProgrammes() as $programme) {
            $manager->remove($programme);
        }
        $manager->remove($session);
        $manager->flush();

        return $this->redirectToRoute("sessions");
    }


}
