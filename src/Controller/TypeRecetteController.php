<?php

namespace App\Controller;

use App\Entity\TypeRecette;
use App\Form\TypeRecetteType;
use App\Repository\TypeRecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/type/recette")
 */
class TypeRecetteController extends AbstractController
{
    /**
     * @Route("/", name="app_type_recette_index", methods={"GET"})
     */
    public function index(TypeRecetteRepository $typeRecetteRepository): Response
    {
        return $this->render('type_recette/index.html.twig', [
            'type_recettes' => $typeRecetteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_type_recette_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TypeRecetteRepository $typeRecetteRepository): Response
    {
        $typeRecette = new TypeRecette();
        $form = $this->createForm(TypeRecetteType::class, $typeRecette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeRecetteRepository->add($typeRecette);
            return $this->redirectToRoute('app_type_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_recette/new.html.twig', [
            'type_recette' => $typeRecette,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_type_recette_show", methods={"GET"})
     */
    public function show(TypeRecette $typeRecette): Response
    {
        return $this->render('type_recette/show.html.twig', [
            'type_recette' => $typeRecette,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_type_recette_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TypeRecette $typeRecette, TypeRecetteRepository $typeRecetteRepository): Response
    {
        $form = $this->createForm(TypeRecetteType::class, $typeRecette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeRecetteRepository->add($typeRecette);
            return $this->redirectToRoute('app_type_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_recette/edit.html.twig', [
            'type_recette' => $typeRecette,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_type_recette_delete", methods={"POST"})
     */
    public function delete(Request $request, TypeRecette $typeRecette, TypeRecetteRepository $typeRecetteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeRecette->getId(), $request->request->get('_token'))) {
            $typeRecetteRepository->remove($typeRecette);
        }

        return $this->redirectToRoute('app_type_recette_index', [], Response::HTTP_SEE_OTHER);
    }
}
