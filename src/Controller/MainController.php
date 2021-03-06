<?php

namespace App\Controller;


use App\Form\SearchForm;
use App\Repository\ActivityRepository;
use App\Service\SearchData;
use App\Service\stateManagement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_index')]
    public function index(ActivityRepository $ar, Request $r,
                          stateManagement $sm, EntityManagerInterface $em): Response
    {
        $data = new SearchData();

        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($r);

        $activities = $ar->findSearch($data);
     /*   foreach ($activities as $activity){
            $activity->setState($sm->setTheState($activity));
            $em->persist($activity);
            $em->refresh($activity);
        }
        $em->flush();*/

        return $this->render('main/index.html.twig',
        [
            'activities' => $activities,
            'form' => $form->createView()
        ]);
    }
}
