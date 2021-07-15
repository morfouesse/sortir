<?php

namespace App\Service;


use App\Entity\Activity;
use App\Entity\State;
use App\Repository\StateRepository;

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

//        $this->states = ['created' => $this->stateRepository->findOneBy(['label' => STATE::TAB_LABEL[0]]),
//            'open' => $this->stateRepository->findOneBy(['label' => STATE::TAB_LABEL[1]]),
//            'closed' => $this->stateRepository->findOneBy(['label' => STATE::TAB_LABEL[2]]),
//            'onGoing' => $this->stateRepository->findOneBy(['label' => STATE::TAB_LABEL[3]]),
//            'past' => $this->stateRepository->findOneBy(['label' => STATE::TAB_LABEL[4]]),
//            'canceled' => $this->stateRepository->findOneBy(['label' => STATE::TAB_LABEL[5]]),
//            'archived' => $this->stateRepository->findOneBy(['label' => STATE::TAB_LABEL[6]]),
//        ];
    }

    public function setTheState(Activity $activity, string $stateToSet = '', array $states = []): State
    {
//        dd($this->canSignIn($activity));

        if ($states == null) {
            $states = $this->states;
        }
        $state = $states[6];

        if ($stateToSet == 'created') {
            $state = $states[0];
        } elseif ($stateToSet == 'open') {
            $state = $states[1];
        }
//        elseif ($stateToSet === 'fixtures') {
//            $rd = random_int(0, 100);
//            if ($rd < 15) {
//                $state = $states[5];
//            } elseif ($rd < 45) {
//                $state = $states[0];
//            } else {
//                $state = $states[1];
//            }
//        }
        if ($activity->getState() == $states[1] || $activity->getState() == $states[2] || $activity->getState() == null) {
            if ($this->activityOnGoing($activity)) {
                $state = $states[3];
            } else if ($this->activityIsNotFull($activity) && $this->limitDateNotPassed($activity)) {
                $state = $states[1];
            } else {
                $state = $states[2];
            }
            if ($stateToSet == 'fixtures') {
                $rd3 = random_int(0, 100);
                if ($rd3 < 15) {
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
        if ($activity->getState() == $states[5] || $activity->getState() == $states[4]) {
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

    public function startDateIsNotPassed(Activity $activity): bool
    {
        return $activity->getStartDateTime() > new \DateTime('now');
    }

    public function activityOnGoing(Activity $activity): bool
    {
        return $activity->getStartDateTime() < new \DateTime('now')
            && $activity->getStartDateTime()->modify('+' . $activity->getDuration() . ' minute') > new \DateTime('now');
    }

    public function activityPast(Activity $activity): bool
    {
        return $activity->getStartDateTime()->modify('+' . $activity->getDuration() . ' minute') < new \DateTime('now');
    }

    public function activityArchieved(Activity $activity): bool
    {
        return $activity->getStartDateTime()->modify('+1 month') < new \DateTime('now');
    }

}
