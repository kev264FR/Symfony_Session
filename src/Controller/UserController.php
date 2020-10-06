<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\RoleType;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 * @IsGranted("ROLE_USER")
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
     * @Route("/password/change", name="change_password")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $encoder){
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (password_verify($form->get("oldPassword")->getData(), $this->getUser()->getPassword())) {
                $ok = $this->getDoctrine()
                            ->getRepository(User::class)
                            ->upgradePassword($this->getUser(), $encoder->encodePassword($this->getUser(), $form->get("newPassword")->getData() ));
                dump($ok);
                return $this->redirectToRoute("app_logout");
            }else{
                $this->addFlash("error", "Mauvais mot de passe");
                return $this->redirectToRoute("change_password");
            }
        }


        return $this->render("security/change_password.html.twig", [
            "form"=>$form->createView()
        ]);
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

    /**
     * @Route("/admin/toogle/{id}", name="admin_toggle")
     * @IsGranted("ROLE_ADMIN")
     */
    public function toggleAdmin(User $user){
        $manager = $this->getDoctrine()->getManager();
        $status = "";
        if($user->hasRole("ROLE_ADMIN")){
            $user->setRoles([]);
            $status = "remove";
        }else{
            $user->setRoles(["ROLE_ADMIN"]);
            $status = "add";
        }

        dump($user);
        $manager->persist($user);
        $manager->flush();
        if ($status == "add") {
            $this->addFlash("success", "L'user est désormais admin");
        }else $this->addFlash("success", "L'user n'est plus admin");
        
        return $this->redirectToRoute("user_detail", ["id"=>$user->getId()]);
    }
}
