<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    #[Route('/activity/showActivity/{id}', name: 'activity_showActivity')]
    public function showActivity(int $id, ActivityRepository $activityRepository): Response
    {
        $activity = $activityRepository->find($id);
        if (!$activity){
            throw $this->createNotFoundException('Cette sortie n\'existe pas');
        }

        return $this->render('activity/showActivity.html.twig', [
            'activity' => $activity
        ]);
    }

    #[Route('/activity/signIn/{id}', name: 'activity_signIn')]
    public function signIn(int $id, ActivityRepository $activityRepository,
                           UserRepository $userRepository, EntityManagerInterface $manager): Response
    {
        $userId = $this->getUser()->getId();
        $user = $userRepository->find($userId);

        $activity = $activityRepository->find($id);
        if (!$activity){
            throw $this->createNotFoundException('Cette sortie n\'existe pas');
        }

        //To test before sign the user in : state : open | limitDate ok | limitInscriptions ok
        $stateIsOpen = $activity->getState()->getLabel() == 'open';
        $limitDateNotPassed = $activity->getInscriptionLimitDate() > new \DateTime('now');
        $activityIsNotFull = count($activity->getUsers()) < $activity->getMaxInscriptionsNb();

        if (true) {
            $activity->addUser($user);
            $user->addActivity($activity);

            $manager->persist($user);
            $manager->persist($activity);
            $manager->flush();

            return $this->render('activity/showActivity.html.twig', [
                'activity' => $activity
            ]);
        } else {
            $errorInscritpionDenied = 'Erreur : il n\'est plus possible de s\'inscrire à cette sortie';
        }
        return $this->render('activity/showActivity.html.twig', [
            'activity' => $activity,
            'errorInscritpionDenied' => $errorInscritpionDenied
        ]);
    }

    #[Route('/activity/signOut/{id}', name: 'activity_signOut')]
    public function signOut(int $id, ActivityRepository $activityRepository,
                            UserRepository $userRepository, EntityManagerInterface $manager): Response
    {
        $userId = $this->getUser()->getId();
        $user = $userRepository->find($userId);

        $activity = $activityRepository->find($id);
        if (!$activity){
            throw $this->createNotFoundException('Cette sortie n\'existe pas');
        }
        $activity->removeUser($user);
        $user->removeActivity($activity);

        $manager->persist($user);
        $manager->persist($activity);
        $manager->flush();

        return $this->render('activity/showActivity.html.twig', [
            'activity' => $activity
        ]);

    }
}
