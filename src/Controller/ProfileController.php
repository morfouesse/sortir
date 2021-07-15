<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Form\EditProfileType;
use App\Repository\UserRepository;
use App\Service\UploadPicture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{


    #[Route('/profile/showProfile/{id}', name: 'profile_showProfile', requirements: ['id' => '\d+'])]
   public function showProfile(int $id, UserRepository $userRepository): Response{
       $user = $userRepository->find($id);

       return $this->render('profile/showProfile.html.twig', [
           'user' => $user
       ]);
   }

    #[Route('/profile/edit', name: 'profile_edit')]
    public function edit(EntityManagerInterface $entityManager,
        Request $request, UploadPicture $uploadPicture): Response
    {
        $editProfilForm = $this->createForm(EditProfileType::class, $this->getUser());
        $editProfilForm->handleRequest($request);

        if ($editProfilForm->isSubmitted() && $editProfilForm->isValid()){

            $pictureName = $editProfilForm->get('pictureName')->getData();
            $pictureNewFileName = $uploadPicture->save(
                $pictureName,
                $this->getParameter('upload_imageName_user_dir'));
            $this->getUser()->setPictureName($pictureNewFileName);
            $entityManager->persist($this->getUser());
            $entityManager->flush();
            // sinon pendant la redirection n'as pas
            // le temps d'afficher l'image correctement
            $entityManager->refresh($this->getUser());

            $this->addFlash('success', 'Votre profil a bien été modifié');
            return $this->redirectToRoute('profile_showProfile',['id' => $this->getUser()->getId()]);
        }

        return $this->render('profile/edit.html.twig', [
            'editProfileForm' => $editProfilForm->createView()
        ]);
    }



    #[Route('/profile/changePassword', name: 'profile_changePassword')]
    public function changePassword(UserPasswordEncoderInterface $encoder, Request $request): Response
    {
        $errorInvalidPassword = null;

        $changePasswordForm = $this->createForm(ChangePasswordType::class);
        $changePasswordForm->handleRequest($request);


        if ($changePasswordForm->isSubmitted() && $changePasswordForm->isValid()){
            $data = $changePasswordForm->getData();

            if ($encoder->isPasswordValid($this->getUser(), $data['currentPassword'])){

                $encodedPassword = $encoder->encodePassword($this->getUser(), $data['newPassword']);
                $this->getUser()->setPassword($encodedPassword);
                $this->getDoctrine()->getManager()->persist($this->getUser());
                $this->getDoctrine()->getManager()->flush();
                $this->getDoctrine()->getManager()->refresh($this->getUser());

                return $this->redirectToRoute('profile_showProfile',['id' => $this->getUser()->getId()]);
            } else {
              $errorInvalidPassword = 'Erreur : mot de passe incorrect';
            }
        }
        return $this->render('profile/changePassword.html.twig', [
            'changePasswordForm' => $changePasswordForm->createView(),
            'errorInvalidPassword' => $errorInvalidPassword
        ]);
    }
}
