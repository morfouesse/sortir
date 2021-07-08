<?php

namespace App\Controller;


use App\Form\SearchForm;
use App\Repository\ActivityRepository;
use App\Service\SearchData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_index')]
    public function index(ActivityRepository $ar, Request $r): Response
    {
        $activities = $ar->findAll();

        $data = new SearchData();
       // $data->setId($this->getUser()->getId());
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($r);



        if($form->isSubmitted() && $form->isValid()){
            dump($activities = $ar->findSearch($data));
        }

        return $this->render('main/index.html.twig',
        [
            'activities' => $activities,
            'form' => $form->createView()
        ]);
    }
}
