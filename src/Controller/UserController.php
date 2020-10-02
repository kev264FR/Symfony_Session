<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/list", name="user_list")
     * @IsGranted("ROLE_ADMIN")
     */
    public function listUsers(){
        $users = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->getAll();
        return $this->render("user/user_list.html.twig", [
            "users"=>$users
        ]);
    }

    /**
     * @Route("/{id}", name="user_detail")
     * @IsGranted("ROLE_ADMIN")
     */
    public function userDetail(User $user){
        return $this->render("user/user_detail.html.twig", [
            "user"=>$user
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUser(User $user, Request $request){
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute("user_detail", ["id"=>$user->getId()]);
        }

        return $this->render("user/user_edit.html.twig", [
            "user"=>$user,
            "form"=>$form->createView()
        ]);
    }
}
