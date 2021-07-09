<?php

namespace App\Service;

use App\Entity\Campus;

class SearchData
{

    /**
     * @var int
     */
    public $id;
    /**
     * @var Campus[]
     */
    public $campuses = [];

    /** for the name of the search
     * @var string
     */
    public $q ="";

    /**
     * @var Date
     */
    public $startDate;

     /**
      * @var Date
      */
    public $lastDate;

    /**
     * @var Boolean
     */
    public $userOwnActivities;

     /**
      * @var Boolean
      */
    public $usersActivities;

     /**
      * @var Boolean
      */
    public $userNotActivities;

    /**
     * @var Boolean
     */
    public $pastActivities;

    public function setId(int $id){
        $this->id = $id;
    }
}