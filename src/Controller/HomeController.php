<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $gifs = [
            "https://media1.giphy.com/media/3o84TTjJSTJlJ6XW5W/giphy.gif",
            "https://media.giphy.com/media/Pgu5gYN5F4ttPtvc3t/giphy.gif",
            "https://media.giphy.com/media/1jZ4rL9C2fFNUPhxbP/giphy.gif",
            "https://media.giphy.com/media/ZZO17GSVMmo3HsphgF/giphy.gif",
            "https://media.giphy.com/media/PWRxAYSAtn3BLPS3Tt/giphy.gif",
            "https://media.giphy.com/media/MWSRkVoNaC30A/giphy.gif"
        ];
        $gif = $gifs[array_rand($gifs)];
        return $this->render('home/index.html.twig', [
            "image"=>$gif
        ]);
    }
}
