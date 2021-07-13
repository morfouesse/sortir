<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\CrudActivityType;
use App\Repository\LocationRepository;
use App\Service\Crud\ActivityAsAUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CrudActivityController extends AbstractController
{
    #[Route('/crud/activity', name: 'crudActivity')]
    public function index(ActivityAsAUser $cu,
        Request $r, LocationRepository $lr): Response
    {

        $activity = new Activity();
        $locations =  $lr->findAll();

        $form = $this->createForm(CrudActivityType::class, $activity);
        $form->handleRequest($r);


        if($form->isSubmitted() && $form->isValid()){

            dd($cu->createActivity($form->getData()));
        }



        return $this->render('crudActivity/index.html.twig', [
            'form' => $form->createView(),
            'locations' => $locations,

        ]);
    }
}
