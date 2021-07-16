<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\CrudActivityType;
use App\Repository\ActivityRepository;
use App\Repository\LocationRepository;
use App\Repository\StateRepository;
use App\Service\Crud\ActivityAsAUser;
use App\Service\stateManagement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CrudActivityController extends AbstractController
{
    #[Route('/crud/createActivity', name: 'crud_createActivity')]
    public function createActivity(ActivityAsAUser $cu,
                                   Request $r, LocationRepository $lr): Response
    {

        $activity = new Activity();
        $locations = $lr->findAll();

        $form = $this->createForm(CrudActivityType::class, $activity);
        $form->handleRequest($r);


        if ($form->isSubmitted() && $form->isValid()) {

            $stateToSet = $form->get('save')->isClicked()
                ? 'created'
                : 'open';

            $id = $cu->createActivity($activity, $stateToSet);

            $this->addFlash('notice', 'La sortie a été créée');
            return $this->redirectToRoute('activity_showActivity', [
                'id' => $id
            ]);
        }

        $this->addFlash('error', 'Erreur à la création de la sortie');
        return $this->render('crudActivity/createActivity.html.twig', [
            'form' => $form->createView(),
            'locations' => $locations,

        ]);
    }

    #[Route('/crud/modifyActivity/{id}', name: 'crud_modifyActivity', requirements: ['id' => '\d+'])]
    public function modifyActivity(EntityManagerInterface $em,
                                   Request $r, ActivityRepository $ar, $id): Response
    {

        $activity = $ar->find($id);
        $form = $this->createForm(CrudActivityType::class, $activity);
        $form->handleRequest($r);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($activity);
            $em->flush();

            $this->addFlash(
                'notice',
                'Sortie modifiée avec succès !'
            );
            return $this->redirectToRoute('activity_showActivity', [
                'id' => $id
            ]);
        }

        return $this->render('crudActivity/modifyActivity.html.twig', [
            'form' => $form->createView(),
            'id' => $id
        ]);
    }

    #[Route('/crud/publish/{id}', name: 'crud_publish', requirements: ['id' => '\d+'])]
    public function publish(int $id, ActivityRepository $activityRepository,
                            stateManagement $stateManagement, EntityManagerInterface $entityManager
    ): RedirectResponse
    {

        $activity = $activityRepository->find($id);
        $activity->setState($stateManagement->setTheState($activity, 'open'));
        $entityManager->persist($activity);
        $entityManager->flush();

        $this->addFlash('notice', 'Votre sortie à été publiée');
        return $this->redirectToRoute('activity_showActivity', [
            'id' => $id
        ]);
    }

    #[Route('/crud/cancelActivity/{id}', name: 'crud_cancelActivity', requirements: ['id' => '\d+'])]
    public function cancelActivity(EntityManagerInterface $em, StateRepository $sr,
                                   ActivityRepository $ar, $id): Response
    {

        $activity = $ar->find($id);
        $activity->setState($sr->findOneBy(['label' => 'canceled']));

        $em->persist($activity);
        $em->flush();

        $this->addFlash(
            'notice',
            'Sortie annulée'
        );
        return $this->redirectToRoute('activity_showActivity', [
            'id' => $id
        ]);
    }


}
