<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Form\EditProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ProfileController extends AbstractController
{
    #[Route('/profile/myProfile', name: 'profile_myProfile')]
    public function myProfile(): Response{
        $user = $this->getUser();
        if (!$user){
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }
        return $this->render('profile/myProfile.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/profile/showProfile/{id}', name: 'profile_showProfile')]
   public function showProfile(int $id, UserRepository $userRepository): Response{
       $user = $userRepository->find($id);
       if (!$user){
           throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
       }
       return $this->render('profile/showProfile.html.twig', [
           'user' => $user
       ]);
   }

    #[Route('/profile/edit', name: 'profile_edit')]
    public function edit(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder, Request $request): Response
    {
        $editProfilForm = $this->createForm(EditProfileType::class, $this->getUser());
        $editProfilForm->handleRequest($request);

        if ($editProfilForm->isSubmitted() && $editProfilForm->isValid()){
            $encodedPassword = $encoder->encodePassword($this->getUser(), $editProfilForm->get('plainPassword')->getData());
            $this->getUser()->setPassword($encodedPassword);
            $entityManager->persist($this->getUser());
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié');
            return $this->redirectToRoute('profile_myProfile');
        }

        return $this->render('profile/edit.html.twig', [
            'editProfileForm' => $editProfilForm->createView()
        ]);
    }



    #[Route('/profile/changePassword', name: 'profile_changePassword')]
    public function changePassword(UserPasswordEncoderInterface $encoder, Request $request): Response
    {
        $changePasswordForm = $this->createForm(ChangePasswordType::class);
        $changePasswordForm->handleRequest($request);


        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()){


            $data = $changePasswordForm->getData();

            if ($encoder->isPasswordValid($this->getUser(), $data['currentPassword'])){

                $encodedPassword = $encoder->encodePassword($this->getUser(), $data['newPassword']);
                $this->getUser()->setPassword($encodedPassword);
                $this->getDoctrine()->getManager()->refresh($this->getUser());
//                $this->getDoctrine()->getManager()->persist($this->getUser());
//                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('profile_myProfile');
            } else {
//              throw new AuthenticationException('Mot de passe courant incorrect');

            }

        }

        return $this->render('profile/changePassword.html.twig', [
            'changePasswordForm' => $changePasswordForm->createView()
        ]);
    }
}
