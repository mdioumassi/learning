<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users", name="users_")
 * Class UsersController
 * @package App\Controller
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function profile()
    {
        return $this->render('users/profile.html.twig');
    }

    /**
     * @Route("/profil/edit", name="edit_profile")
     * @param Request $request
     */
    public function editProfile(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $this->getUser());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('users_profile');
        }

        return $this->render('users/editprofile.html.twig', [
           'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/password/edit", name="passwd_edit")
     */
    public function editpas(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $manager)
    {
        if($request->isMethod('POST')) {
            $user = $this->getUser();

            //On vérifie si les 2 mots de passe sont identiques
            if($request->request->get('pass') == $request->request->get('pass2')) {
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $manager->flush();
                $this->addFlash('message', 'Mot de passe mis à jour avec succés');
                return $this->redirectToRoute('users_profile');
            } else {
                $this->addFlash('error', 'Les deux mots de passe  ne sont pas identiques');
            }
        }
        return $this->render('users/editpass.html.twig');
    }

    /**
     * @Route("/dashbord", name="dashbord")
     */
    public function dashboard()
    {
       return $this->render("users/dashbord.html.twig");
    }
}
