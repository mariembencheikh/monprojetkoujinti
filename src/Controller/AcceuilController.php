<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AcceuilController extends AbstractController
{
    /**
     * @Route("/", name="app_acceuil")
     */
    public function index(MenuRepository $menuRepository): Response
    {
        return $this->render('acceuil/index.html.twig', [
            'menus' => $menuRepository->findAll(),
        ]);
    }
}
