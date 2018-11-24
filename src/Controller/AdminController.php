<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("", name="admin_index")
     */
    public function index()
    {
        return $this->redirectToRoute('admin_dashboard');
    }

    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function dashboard(UserRepository $userRepo)
    {
        $userList = $userRepo->findWithProductsAndLoans();
        
        // $userList = $userRepo->findBy(['registerDate' => new Datetime('now')], 'registerDate DESC', 10)
        
        return $this->render("admin/dashboard.html.twig",[
            'users' => $userList
        ]);
    }
    
    /**
     * @Route("/user/delete/{id}", name="delete_user")
     */
    public function deleteUser(User $user, ObjectManager $manager)
    {
        $manager->remove($user);
        $manager->flush();
        return $this->redirectToRoute('admin_dashboard');
    }
    
    /**
     * @Route("/user/add", name="add_user")
     * @Route("/user/edit/{id}", name="edit_user")
     */
    public function editUser(Request $request, ObjectManager $manager, User $user = null)
    {
        if($user === null){
            $user = new User();
        }
        $formUser = $this->createForm(UserType::class, $user)
                ->add('Envoyer', SubmitType::class);
        
        $formUser->handleRequest($request); // déclenche la gestion du formulaire
        
        if($formUser->isSubmitted() && $formUser->isValid()){
            // enregistrement de notre utilisateur
            $user->setRegisterDate(new \DateTime('now'));
            $user->setRoles('ROLE_USER');
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('admin_dashboard');
        }
        
        return $this->render('admin/edit_user.html.twig',[
            'form' => $formUser->createView()
        ]);
    }
}
