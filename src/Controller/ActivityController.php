<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Repository\UserRepository;
use App\Service\stateManagement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    #[Route('/activity/showActivity/{id}', name: 'activity_showActivity', requirements: ['id' => '\d+'])]
    public function showActivity(int $id, ActivityRepository $activityRepository,
                                 stateManagement $stateManagement, EntityManagerInterface $entityManager): Response
    {
        $activity = $activityRepository->find($id);
        $activity->setState($stateManagement->setTheState($activity));
        $entityManager->persist($activity);
        $entityManager->flush();

        return $this->render('activity/showActivity.html.twig', [
            'activity' => $activity
        ]);
    }

    #[Route('/activity/signIn/{id}', name: 'activity_signIn', requirements: ['id' => '\d+'])]
    public function signIn(int $id, ActivityRepository $activityRepository,
                           UserRepository $userRepository, EntityManagerInterface $manager,
                           stateManagement $valid
    ): Response
    {
        $activity = $activityRepository->find($id);


        if ($valid->canSignIn($activity)) {
            $activity->addUser($this->getUser());
            $this->getUser()->addActivity($activity);

            $manager->persist($this->getUser());
            $manager->persist($activity);
            $manager->flush();

            $manager->refresh($this->getUser());

            $this->addFlash('notice', 'Vous êtes inscrit');

        }
            return $this->redirectToRoute('activity_showActivity', [
                'id' => $activity->getId(),
            ]);
    }

    #[Route('/activity/signOut/{id}', name: 'activity_signOut', requirements: ['id' => '\d+'])]
    public function signOut(int $id, ActivityRepository $activityRepository,
                            UserRepository $userRepository, EntityManagerInterface $manager): Response
    {
        $activity = $activityRepository->find($id);
        if (!$activity) {
            throw $this->createNotFoundException('Cette sortie n\'existe pas');
        }
        $activity->removeUser($this->getUser());
        $this->getUser()->removeActivity($activity);

        $manager->persist($this->getUser());
        $manager->persist($activity);
        $manager->flush();

        $manager->refresh($this->getUser());

        $this->addFlash('notice', 'Vous êtes désinscrit');
        return $this->render('activity/showActivity.html.twig', [
            'activity' => $activity
        ]);

    }
}
