<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    private $repos;
    public function __construct(UserRepository $repos)
    {
       $this->repos = $repos;
    }
    /**
     * @Route("/login", name="login")
     */
    

    public function index(AuthenticationUtils $authenticationUtils): Response
    {
       // get the login error if there is one
         $error = $authenticationUtils->getLastAuthenticationError();

         // last username entered by the user
         $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
             'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }   
    /**
     * @Route("/compte", name="app_compte", methods={"GET"})
     */
    public function compte()
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        // controller can be blank: it will never be called!
        return $this->render('login/compte.html.twig');
    } 
     /**
     * @Route("/users", name="app_all_user")
     * @IsGranted("ROLE_ADMIN")

     */
    public function allUser(UserRepository $userRepository): Response
    {
        return $this->render('login/alluser.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }  


    
    /**
     * @Route("/{id}", name="app_user_show",requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function detail($id): Response
    {
        $user = $this->repos->find($id);
        if (!$user) {
             throw $this->createNotFoundException('Cet utilisateur est inexistant');
             
            // the above is just a shortcut for:
            // throw new NotFoundHttpException('The product does not exist');
         }
             
      //  $produits = $repos->chercherParIntervallePrix(10,1000);
       // dd($produits);
       return $this->render('login/profileUser.html.twig', ["user" => $user]);
    }  
}
