<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\State;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        //Fixtures for entity Campus
        //_____________________________________________
        $campus1 = new Campus();
        $campus1->setName('Saint-Herblain');
        $campus2 = new Campus();
        $campus2->setName('Chartres de Bretagne');
        $campus3 = new Campus();
        $campus3->setName('La Roche sur Yon');

        $manager->persist($campus1);
        $manager->persist($campus2);
        $manager->persist($campus3);
        //_____________________________________________


        //Fixtures for entity User
        //_____________________________________________
        $user1 = new User();
        $user1->setCampus($campus1)
            ->setUsername('user1')
            ->setEmail('email1@dom.com')
            ->setName('lastName1')
            ->setFirstName('firstname1')
            ->setPhone('0601025501');
        $password1 = $this->encoder->encodePassword($user1, 'password1');
        $user1->setPassword($password1);

        $user2 = new User();
        $user2->setCampus($campus1)
            ->setUsername('user2')
            ->setEmail('email2@dom.com')
            ->setName('lastName2')
            ->setFirstName('firstname2')
            ->setPhone('0242857643');
        $password2 = $this->encoder->encodePassword($user2, 'password2');
        $user2->setPassword($password2);

        $user3 = new User();
        $user3->setCampus($campus3)
            ->setUsername('user3')
            ->setEmail('email3@dom.com')
            ->setName('lastName3')
            ->setFirstName('firstname3')
            ->setPhone('0745869210');
        $password3 = $this->encoder->encodePassword($user3, 'password3');
        $user3->setPassword($password3);

        //persist() is done after activities' one because of manyToMany relation

        //_____________________________________________


        //Fixtures for entity City
        //_____________________________________________
        $city1 = new City();
        $city1->setName('Nantes')
            ->setPostalCode('44000');
        $city2 = new City();
        $city2->setName('Rennes')
            ->setPostalCode('35000');
        $city3 = new City();
        $city3->setName('La Roche sur Yon')
            ->setPostalCode('85000');
        $manager->persist($city1);
        $manager->persist($city2);
        $manager->persist($city3);
        //_____________________________________________


        //Fixtures for entity Location
        //_____________________________________________
        $location1 = new Location();
        $location1->setName('location 1')
            ->setStreet('11 rue1')
            ->setLatitude(4.358782)
            ->setLongitude(2.050278)
            ->setCity($city1);
        $location2 = new Location();
        $location2->setName('location 2')
            ->setStreet('22 rue2')
            ->setLatitude(7.134058)
            ->setLongitude(9.014714)
            ->setCity($city2);

        $location3 = new Location();
        $location3->setName('location3')
            ->setStreet('33 ru3')
            ->setLatitude(1.052520)
            ->setLongitude(2.360548)
            ->setCity($city3);

        $manager->persist($location1);
        $manager->persist($location2);
        $manager->persist($location3);
        //_____________________________________________


        //Fixtures for entity State
        //_____________________________________________
        $state1 = new State();
        $state1->setLabel(State::TAB_LABEL[0]);
        $manager->persist($state1);

        $state2 = new State();
        $state2->setLabel(State::TAB_LABEL[1]);
        $manager->persist($state2);

        $state3 = new State();
        $state3->setLabel(State::TAB_LABEL[2]);
        $manager->persist($state3);

        $state4 = new State();
        $state4->setLabel(State::TAB_LABEL[3]);
        $manager->persist($state4);

        $state5 = new State();
        $state5->setLabel(State::TAB_LABEL[4]);
        $manager->persist($state5);

        $state6 = new State();
        $state6->setLabel(State::TAB_LABEL[5]);
        $manager->persist($state6);
        //_____________________________________________

        //Fixtures for entity Activity
        //_____________________________________________
        $activity1 = new Activity();
        $activity1->setLocation($location1)
            ->setCampus($campus1)
            ->setUserOwner($user1)
            ->addUser($user2)
            ->setState($state1)
            ->setName('activity1')
            ->setStartDateTime(new \DateTime('2021-07-14'))
            ->setDuration(4)
            ->setInscriptionLimitDate(new \DateTime('2021-07-12'))
            ->setMaxInscriptionsNb(10)
            ->setActivityInfo('Sortie du 14 juillet');

        $activity2 = new Activity();
        $activity2->setLocation($location2)
            ->setCampus($campus2)
            ->setUserOwner($user2)
            ->addUser($user1)->addUser($user3)
            ->setState($state1)
            ->setName('activity2')
            ->setStartDateTime(new \DateTime('2021-09-04'))
            ->setDuration(5)
            ->setInscriptionLimitDate(new \DateTime('2021-09-04'))
            ->setMaxInscriptionsNb(25)
            ->setActivityInfo('Pot de rentrée');

        $activity3 = new Activity();
        $activity3->setLocation($location3)
            ->setCampus($campus3)
            ->setUserOwner($user3)
            //No addUser()
            ->setState($state1)
            ->setName('activity3')
            ->setStartDateTime(new \DateTime('2021-12-25'))
            ->setDuration(6)
            ->setInscriptionLimitDate(new \DateTime('2021-12-15'))
            ->setMaxInscriptionsNb(15)
            ->setActivityInfo('Repas de noël');

        $activity4 = new Activity();
        $activity4->setLocation($location1)
            ->setCampus($campus1)
            ->setUserOwner($user1)
            ->addUser($user2)->addUser($user3)
            ->setState($state1)
            ->setName('activity4')
            ->setStartDateTime(new \DateTime('2021-07-28'))
            ->setDuration(4)
            ->setInscriptionLimitDate(new \DateTime('2021-07-17'))
            ->setMaxInscriptionsNb(20)
            ->setActivityInfo('Promenade en fôret');

        $activity5 = new Activity();
        $activity5->setLocation($location1)
            ->setCampus($campus1)
            ->setUserOwner($user3)
            ->addUser($user1)
            ->setState($state1)
            ->setName('activity5')
            ->setStartDateTime(new \DateTime('2021-08-08'))
            ->setDuration(2)
            ->setInscriptionLimitDate(new \DateTime('2021-08-08'))
            ->setMaxInscriptionsNb(6)
            ->setActivityInfo('Aqua-poney à la piscine');

        $activity6 = new Activity();
        $activity6->setLocation($location1)
            ->setCampus($campus3)
            ->setUserOwner($user3)
            ->addUser($user1)->addUser($user2)
            ->setState($state1)
            ->setName('activity6')
            ->setStartDateTime(new \DateTime('2021-10-01'))
            ->setDuration(3)
            ->setInscriptionLimitDate(new \DateTime('2021-10-01'))
            ->setMaxInscriptionsNb(12)
            ->setActivityInfo('Cinéma');

                //Adding activities to users
                //___________________
                $user1->addActivity($activity2)->addActivity($activity5)->addActivity($activity5);
                $user2->addActivity($activity1)->addActivity($activity4)->addActivity($activity6);
                $user3->addActivity($activity2)->addActivity($activity4);

                $manager->persist($user1);
                $manager->persist($user2);
                $manager->persist($user3);
                //___________________

        $manager->persist($activity1);
        $manager->persist($activity2);
        $manager->persist($activity3);
        $manager->persist($activity4);
        $manager->persist($activity5);
        $manager->persist($activity6);
        //_____________________________________________


        $manager->flush();
    }
}