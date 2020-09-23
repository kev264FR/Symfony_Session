<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Module;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/module")
 */
class ModuleController extends AbstractController
{
    /**
     * @Route("/categories", name="modules_categories")
     */
    public function index()
    {
        $categories = $this->getDoctrine()
                        ->getRepository(Categorie::class)
                        ->findAll();

        return $this->render('module/index.html.twig', [
            "categories"=>$categories
        ]);
    }

    /**
     * @Route("/categorie/new", name="new_categorie")
     * @Route("/categorie/edit/{id}", name="edit_categorie")
     */
    public function addCategorie(Request $request, Categorie $categorie = null){

        $edit = false;

        if ($categorie) {
            $edit = true;
        }else{
            $categorie = new Categorie();
        }
        
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$edit) {
                $categorie->setUser($this->getUser());
            }

            $manager->persist($categorie);
            $manager->flush();

            return $this->redirectToRoute("modules_categories");

        }
        return $this->render("module/categorie_form.html.twig", [
            "form"=>$form->createView(),
            "edit"=>$edit
        ]);
    }
}
