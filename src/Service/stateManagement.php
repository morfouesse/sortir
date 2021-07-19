<?php

namespace App\Service;


use App\Entity\Activity;
use App\Entity\State;
use App\Repository\StateRepository;
use DateTime;

class stateManagement
{
    private StateRepository $stateRepository;
    private array $states;

    public function __construct(StateRepository $stateRepository)
    {
        $this->stateRepository = $stateRepository;

        for ($i = 0; $i < 7; $i++) {
            $this->states[] = $this->stateRepository->findOneBy(['label' => STATE::TAB_LABEL[$i]]);
        }


    }

    public function setTheState(Activity $activity, string $stateToSet = '', array $states = []): State
    {
        $state = $activity->getState();

        if ($states == null) {
            $states = $this->states;
        }

        if ($stateToSet == 'fixtures') {
            $state = $states[1];
            $activity->setState($state);
        }

        if ($stateToSet == 'created') {
            $state = $states[0];
        }

        if ($stateToSet == 'open') {
            $state = $states[1];
        }

        if ($state == $states[1] || $state == $states[2]) {
            if ($this->activityOnGoing($activity)) {
                $state = $states[3];
            } else if ($this->activityIsNotFull($activity) && $this->limitDateNotPassed($activity)) {
                $state = $states[1];
            } else {
                $state = $states[2];
            }
            if ($stateToSet == 'fixtures') {
                $rd1 = random_int(0, 100);
                if ($rd1 < 15) {
                    $state = $states[5];
                }
            }
        }
        if ($this->activityPast($activity)) {
            $state = $states[4];
            if ($stateToSet == 'fixtures') {
                $rd2 = random_int(0, 100);
                if ($rd2 < 15) {
                    $state = $states[5];
                }
            }
        }
        if ($state == $states[5] || $state == $states[4]) {
            if ($this->activityArchieved($activity)) {
                $state = $states[6];
            }
        }
        return $state;
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
        return $activity->getState()->getLabel() === 'Ouverte';
    }

    public
    function limitDateNotPassed(Activity $activity): bool
    {
        return $activity->getInscriptionLimitDate() > new DateTime('now');
    }

    public
    function activityIsNotFull(Activity $activity): bool
    {
        return count($activity->getUsers()) < $activity->getMaxInscriptionsNb();
    }

    public function startDateIsNotPassed(Activity $activity): bool
    {
        return $activity->getStartDateTime() > new DateTime('now');
    }

    public function activityOnGoing(Activity $activity): bool
    {
        return $activity->getStartDateTime() < new DateTime('now')
            && $activity->getStartDateTime()->modify('+' . $activity->getDuration() . ' minute') > new DateTime('now');
    }

    public function activityPast(Activity $activity): bool
    {
        return $activity->getStartDateTime()->modify('+' . $activity->getDuration() . ' minute') < new DateTime('now');
    }

    public function activityArchieved(Activity $activity): bool
    {
        return $activity->getStartDateTime()->modify('+1 month') < new DateTime('now');
    }

}
