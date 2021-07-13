<?php

namespace App\Service\Crud;



use App\Entity\Activity;
use App\Entity\State;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ActivityAsAUser
{
    private ActivityRepository $ar;
    private EntityManagerInterface $em;
    private Security $s;

    public function __construct(ActivityRepository $ar,
        EntityManagerInterface $em,
        Security $s)
    {
        $this->ar = $ar;
        $this->em = $em;
        $this->s = $s;
    }

    public function createActivity($data)
    {

        $activity = new Activity();
        $activity->setName($data->getName());
        $activity->setActivityInfo($data->getActivityInfo());
        $activity->setInscriptionLimitDate($data->getInscriptionLimitDate());
        $activity->setStartDateTime($data->getStartDateTime());
        $activity->setCampus($this->s->getUser()->getCampus());
        $activity->setLocation($data->getLocation());
        $activity->setMaxInscriptionsNb($data->getMaxInscriptionsNb());
        $activity->setUserOwner($this->s->getUser());
        $activity->setDuration($data->getDuration());

        $state = new State();
        $state->setLabel(STATE::TAB_LABEL[1]);

        $activity->setState($state);
        dd($activity);
      /*  $this->em->persist();
        $this->em->flush();*/
    }
}