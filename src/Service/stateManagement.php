<?php

namespace App\Service;


use App\Entity\Activity;
use App\Repository\StateRepository;

class stateManagement
{

    public function setState(Activity $activity, StateRepository $stateRepository): Activity
    {

        $states = ['created' => $stateRepository->findOneBy(['label' => 'created']),
            'open' => $stateRepository->findOneBy(['label' => 'open']),
            'closed' => $stateRepository->findOneBy(['label' => 'closed']),
            'onGoing' => $stateRepository->findOneBy(['label' => 'onGoing']),
            'past' => $stateRepository->findOneBy(['label' => 'past']),
            'canceled' => $stateRepository->findOneBy(['label' => 'canceled']),
            'archieved' => $stateRepository->findOneBy(['label' => 'archieved']),
        ];


        if ($activity->getState() == $states['archieved']) {
        }
        if ($activity->getState() == $states['canceled'] || $activity->getState() == $states['past']) {
            if ($activity->getStartDateTime()->modify('+1 month') > new \DateTime('now')) {
                $activity->setState($states['archieved']);
            }
        } elseif ($activity->getState() == $states['onGoing']) {
            if ($activity->getStartDateTime()->modify($activity->getDuration() . ' minute') > new \DateTime('now')) {
                $activity->setState($states['past']);
            }
        } elseif ($activity->getState() == $states['open'] || $activity->getState() == $states['closed']) {
            if (!$this->startDateIsNotPassed($activity)) {
                $activity->setState($states['onGoing']);
            } else {
                if ($this->activityIsNotFull($activity) && $this->limitDateNotPassed($activity)) {
                    $activity->setState($states['open']);
                } else {
                    $activity->setState($states['closed']);
                }
            }
        }
        return $activity;
    }


    public
    function canSignIn(Activity $activity): bool
    {
        //To test before sign the user in : state : open | limitDate ok | limitInscriptions ok
        if ($this->stateIsOpen($activity) && $this->limitDateNotPassed($activity) && $this->activityIsNotFull($activity)) {
            return true;
        } else {
            return false;
        }
    }

    public
    function stateIsOpen(Activity $activity): bool
    {
        return $activity->getState()->getLabel() === 'open';
    }

    public
    function limitDateNotPassed(Activity $activity): bool
    {
        return $activity->getInscriptionLimitDate() > new \DateTime('now');
    }

    public
    function activityIsNotFull(Activity $activity): bool
    {
        return count($activity->getUsers()) < $activity->getMaxInscriptionsNb();
    }

    public
    function startDateIsNotPassed(Activity $activity): bool
    {
        return $activity->getStartDateTime() > new \DateTime('now');
    }


}
