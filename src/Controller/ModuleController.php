<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/module")
 * @IsGranted("ROLE_USER")
 */
class ModuleController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
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

            return $this->redirectToRoute("categories");

        }
        return $this->render("module/categorie_form.html.twig", [
            "form"=>$form->createView(),
            "edit"=>$edit
        ]);
    }

    /**
     * @Route("/categorie/delete/{id}", name="delete_categorie")
     */
    public function deleteCategorie(Categorie $categorie = null){
        if (!$categorie) {
            $this->addFlash("error","Action impossible");
            return $this->redirectToRoute("home");
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($categorie);
        $manager->flush();

        return $this->redirectToRoute("categories");
    }

    /**
     * @Route("/categorie/{id}", name="modules_in_categorie")
     */
    public function modulesList(Categorie $categorie = null){
        if (!$categorie) {
            $this->addFlash("error","Action impossible");
            return $this->redirectToRoute("home");
        }

        return $this->render("module/modules_in_categorie.html.twig", [
            "categorie"=>$categorie
        ]);
    }

    /**
     * @Route("/new", name="new_module")
     * @Route("/edit/{id}", name="edit_module")
     * @ParamConverter("module", options={"id"="id"} )
     * @Route("/new/categorie/{cat_id}", name="new_in_categorie")
     * @ParamConverter("categorie", options={"id"="cat_id"} )
     */
    public function newModule(Request $request, Module $module = null, Categorie $categorie = null){

        $manager = $this->getDoctrine()->getManager();
        $edit = false;
        
        if (!$module) {
            $module = new Module();
            if ($categorie) {
                $module->setCategorie($categorie);
            }
        }else{
            $edit = true;
        }
        
        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$edit) {
                $module->setUser($this->getUser());
            }
            $manager->persist($module);
            $manager->flush();
            
            return $this->redirectToRoute("modules_in_categorie", ["id"=>$categorie->getId()]);
        }

        return $this->render("module/module_form.html.twig", [
            "edit"=>$edit,
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_module")
     */
    public function deleteModule(Module $module = null){
        if (!$module) {
            $this->addFlash("error","Action impossible");
            return $this->redirectToRoute("home");
        }
        $manager = $this->getDoctrine()->getManager();
        $categorie = $module->getCategorie();
        $manager->remove($module);
        $manager->flush();
        return $this->redirectToRoute("modules_in_categorie", ["id"=>$categorie->getId()]);
    }



}
