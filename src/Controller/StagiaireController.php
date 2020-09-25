<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stagiaire")
 */
class StagiaireController extends AbstractController
{
    /**
     * @Route("/", name="stagiaires")
     */
    public function index()
    {
        $stagiaires = $this->getDoctrine()
                            ->getRepository(Stagiaire::class)
                            ->findAll();

        return $this->render('stagiaire/index.html.twig', [
            "stagiaires"=>$stagiaires
        ]);
    }

    /**
     * @Route("/new", name="new_stagiaire")
     * @Route("/edit/{id}", name="edit_stagiaire")
     */
    public function addStagiaire(Request $request, Stagiaire $stagiaire = null){
        $manager = $this->getDoctrine()->getManager();
        $edit = false;

        if ($stagiaire) {
            $edit = true;
        }else $stagiaire = new Stagiaire();
        
        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$edit) {
                $stagiaire->setUser($this->getUser());
            }

            $manager->persist($stagiaire);
            $manager->flush();

            return $this->redirectToRoute("stagiaires");
        }

        return $this->render("stagiaire/stagiaire_form.html.twig", [
            "edit"=>$edit,
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/detail/{id}", name="stagiaire")
     */
    public function detailStagiaire(Stagiaire $stagiaire){
        return $this->render("stagiaire/detail_stagiaire.html.twig", [
            "stagiaire"=>$stagiaire
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_stagiaire")
     */
    public function deleteStagiaire(Stagiaire $stagiaire){
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($stagiaire);
        $manager->flush();

        return $this->redirectToRoute("stagiaires");
    }
}
