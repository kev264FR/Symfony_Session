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
            if ($session->getDateDebut() > $session->getDateFin()) {
                $this->addFlash("error", "Date invalide");
                if ($edit) {
                    return $this->redirectToRoute("edit_session", ["id"=>$session->getId()]);
                }else return $this->redirectToRoute("new_session");
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
    public function detailSession(Session $session = null){
        if (!$session) {
            $this->addFlash("error","Action impossible");
            return $this->redirectToRoute("home");
        }
        if (count($session->getStagiaires()) > $session->getPlace()) {
            $this->addFlash("error", "Nombre de stagiaires suppérieure au nombre de place");
            return $this->redirectToRoute("stagiaires_in_session", ["id"=>$session->getId()]);
        }

        return $this->render("formation/detail_session.html.twig", [
            "session"=>$session
        ]);
    }

    /**
     * @Route("/stagiaires/{id}", name="stagiaires_in_session")
     */
    public function addStagiaire(Request $request, Session $session = null){
        if (!$session) {
            $this->addFlash("error","Action impossible");
            return $this->redirectToRoute("home");
        }

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

            if (count($session->getStagiaires()) / 2 > $session->getPlace()) {
                $this->addFlash("error", "Nombre de stagiaires suppérieure au nombre de place");
                return $this->redirectToRoute("stagiaires_in_session", ["id"=>$session->getId()]);
            }
            
            foreach ($session->getStagiaires() as $stagiaire) {
                foreach ($stagiaire->getinscription() as $formation) {
                    if ($session != $formation) {
                        if (($session->getDateDebut() <= $formation->getDateFin()) && ($session->getDateFin() >= $formation->getDateDebut())) {
                            $this->addFlash("error", "erreur un ou plusieurs des stagiaires sont indisponible");
                            return $this->redirectToRoute("stagiaires_in_session", ["id"=>$session->getId()]);
                        }
                    }
                }
            }

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
    public function newProgramme(Request $request, Session $session = null){
        if (!$session) {
            $this->addFlash("error","Action impossible");
            return $this->redirectToRoute("home");
        }

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
            $dureeTotale = 0;
            foreach ($session->getProgrammes() as $prgrm) {
                $prgrm->setUser($this->getUser());
                $prgrm->setSession($session);
                $manager->persist($prgrm);
                $dureeTotale = $dureeTotale + $prgrm->getDuree();
            }
            $manager->flush();
            
            if ($dureeTotale > $session->sessionDuree() / 5 ) {
                $this->addFlash("error", "Durée des modules supérieure a la durée de la formation");
                return $this->redirectToRoute("session_programme", ["id"=>$session->getId()]);
            }
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
    public function deleteSession(Session $session = null){
        if (!$session) {
            $this->addFlash("error","Action impossible");
            return $this->redirectToRoute("home");
        }
        
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
