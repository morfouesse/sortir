<?php

namespace App\Service\Crud;



use App\Entity\Activity;
use App\Repository\ActivityRepository;
use App\Service\stateManagement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ActivityAsAUser
{
    private ActivityRepository $ar;
    private EntityManagerInterface $em;
    private Security $s;
    private stateManagement $sm;

    public function __construct(ActivityRepository $ar,
        EntityManagerInterface $em,
        Security $s,
        stateManagement $sm)
    {
        $this->ar = $ar;
        $this->em = $em;
        $this->s = $s;
        $this->sm = $sm;
    }

    public function createActivity(Activity $activity, string $stateToSet): int
    {
        $activity->setCampus($this->s->getUser()->getCampus());
        $activity->setUserOwner($this->s->getUser());

        $this->sm->setTheState($activity, $stateToSet);

        $this->em->persist($activity);
        $this->em->flush();
        $this->em->refresh($activity);
        return $activity->getId();
    }
}