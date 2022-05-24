<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\TypeRecetteType;

/**
 * @Route("/recette")
 */
class RecetteController extends AbstractController
{
    /**
     * @Route("/", name="app_recette_index", methods={"GET"})
     */
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_recette_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RecetteRepository $recetteRepository, SluggerInterface $slugger): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recette = $form->getData();
            $image = $form->get('image')->getData();

            
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $recette->setImage($newFilename);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($recette);
            $em->flush();
            return $this->redirectToRoute('app_recette_index');
        }

        return $this->renderForm('recette/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_recette_show", methods={"GET"})
     */
    public function show(Recette $recette): Response
    {
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_recette_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Recette $recette, RecetteRepository $recetteRepository,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recetteRepository->add($recette);
            $image = $form->get('image')->getData();

            
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $recette->setImage($newFilename);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($recette);
            $em->flush();
            return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
       
    }

    /**
     * @Route("/delete/{id}", name="app_recette_delete")
     */
    public function delete($id): Response
    {
       /* if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $recetteRepository->remove($recette);
        }
       return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);

        return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);*/
        $entityManager = $this->getDoctrine()->getManager();
        $repos = $this->getDoctrine()->getRepository(Recette::class);
        $recette = $repos->find($id);
        $entityManager->remove($recette);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Le produit a ete supprimer avec succes'
        );
        return $this->redirectToRoute('app_recette_index');
    }
}
