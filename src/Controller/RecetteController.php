<?php

namespace App\Controller;

use DateTime;
use App\Entity\Menu;
use App\Entity\User;
use App\Form\MenuType;
use App\Entity\Recette;
use App\Form\RecetteType;
use App\Entity\Commentaires;
use App\Form\CommentFormType;
use App\Form\TypeRecetteType;
use App\Repository\MenuRepository;
use App\Repository\RecetteRepository;
use App\Repository\TypeRecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/recette")
 */
class RecetteController extends AbstractController{
    
    private $repos;
    public function __construct(RecetteRepository $repos)
    {
       $this->repos = $repos;
    }
    /**
     * @Route("/", name="app_recette_index", methods={"GET"})
     */
    public function index(RecetteRepository $recetteRepository,MenuRepository $menuRepository ,TypeRecetteRepository $typeRecetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
            'menus' => $menuRepository->findAll(),
            'type_recettes' => $typeRecetteRepository->findAll(),

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
     * @Route("/new_menu", name="app_menu_new", methods={"GET", "POST"})
    * @IsGranted("ROLE_ADMIN")

     */
    public function menu(Request $request, MenuRepository $menuRepository, SluggerInterface $slugger): Response
    {
            
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menu = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();
            return $this->redirectToRoute('app_recette_index');
        }

        return $this->renderForm('recette/menu.html.twig', [
            'form' => $form,
        ]);
    }

/**
     * @Route("/menu/{id}", name="app_menu_show", methods={"GET"})
     */
    public function showMenu(Menu $menu): Response
    {
        return $this->render('base2.html.twig', [
            'menu' => $menu,
        ]);
    }
    /**
     * @Route("/{id}", name="app_recette_show", methods={"GET", "POST"})
     */
    public function show(Recette $recette, Request $request): Response
    {
        $comment=new Commentaires();
        //form
        $form=$this->createForm(CommentFormType::Class,$comment);
        $form->handleRequest($request);

        

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $user = $this->getUser();
            $comment->setCreatedAt(new DateTime());
            $comment->setRecettes($recette);
            $comment->setEmail($user->getEmail());
            $comment->setNom($user->getNom());


            $parentId= $form->get("parentid")->getData();

            $em = $this->getDoctrine()->getManager();
            if($parentId!=null){
                $parent= $em->getRepository(Commentaires::Class)->find($parentId);

            }
            $comment->setParent($parent ?? null);
            $em->persist($comment);
            $em->flush();
            $this->addFlash('message','Votre commentaire est envoyé');
            
        
        }
 
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'form'=>$form->createView()
        ]);
    }
    

    /**
     * @Route("/{id}/edit", name="app_recette_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
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
