<?php

namespace App\Service;

use App\Entity\Activity;

class signingValidation{

    public function canSignIn(Activity $activity): bool{
        //To test before sign the user in : state : open | limitDate ok | limitInscriptions ok
        $stateIsOpen = $activity->getState()->getLabel() == 'open';
        $limitDateNotPassed = $activity->getInscriptionLimitDate() > new \DateTime('now');
        $activityIsNotFull = count($activity->getUsers()) < $activity->getMaxInscriptionsNb();

        if ($stateIsOpen && $limitDateNotPassed && $activityIsNotFull){
            return true;
        } else {
            return false;
        }
    }



}