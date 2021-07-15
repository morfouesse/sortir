<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\CrudActivityType;
use App\Repository\ActivityRepository;
use App\Repository\LocationRepository;
use App\Service\Crud\ActivityAsAUser;
use App\Service\stateManagement;
use Doctrine\ORM\EntityManagerInterface;
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

            $stateToSet = $form->get('save')->isClicked()
                ? 'created'
                : 'open';

            $id = $cu->createActivity($activity, $stateToSet);

            $this->addFlash('success', 'La sortie a été créée');
            return $this->redirectToRoute('activity_showActivity', [
                'id' => $id
            ]);
        }

        $this->addFlash('error', 'Erreur à la création de la sortie');
        return $this->render('crudActivity/index.html.twig', [
            'form' => $form->createView(),
            'locations' => $locations,

        ]);
    }

    #[Route('/crud/publish/{id}', name: 'crudActivity_publish', requirements: ['id' => '\d+'])]
    public function publish(int $id, ActivityRepository $activityRepository,
                            stateManagement $stateManagement, EntityManagerInterface $entityManager
    ): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        $activity = $activityRepository->find($id);
        $stateManagement->setTheState($activity, 'open');
        $entityManager->persist($activity);
        $entityManager->flush();

        $this->addFlash('error', 'Votre sortie à été publiée');
        return $this->redirectToRoute('activity_showActivity', [
            'id' => $id
        ]);
    }


}
