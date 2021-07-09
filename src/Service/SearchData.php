<?php

namespace App\Service;

use App\Entity\Campus;

class SearchData
{

    /**
     * @var integer
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
     * @var integer
     */
    public $userOwnActivities;

     /**
      * @var integer
      */
    public $usersActivities;

     /**
      * @var integer
      */
    public $userNotActivities;

    /**
     * @var integer
     */
    public $pastActivities;

    public function setId(int $id){
        $this->id = $id;
    }
}