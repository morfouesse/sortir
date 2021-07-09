<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
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
    public function signIn(int $id, ActivityRepository $activityRepository, UserRepository $userRepository): Response
    {
        $activity = $activityRepository->find($id);
        if (!$activity){
            throw $this->createNotFoundException('Cette sortie n\'existe pas');
        }

        $activity->addUser($this->getUser());
        $this->getUser()->addActivity($activity);

        return $this->render('activity/showActivity.html.twig', [
            'activity' => $activity
        ]);
    }

    #[Route('/activity/signOut/{id}', name: 'activity_signOut')]
    public function signOut(int $id, ActivityRepository $activityRepository): Response
    {
        $activity = $activityRepository->find($id);
        if (!$activity){
            throw $this->createNotFoundException('Cette sortie n\'existe pas');
        }

        $activity->removeUser($this->getUser());
        $this->getUser()->removeActivity($activity);

        return $this->render('activity/showActivity.html.twig', [
            'activity' => $activity
        ]);

    }
}
