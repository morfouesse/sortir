<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\State;
use App\Entity\User;
use App\Repository\ActivityRepository;
use App\Repository\CampusRepository;
use App\Repository\CityRepository;
use App\Repository\LocationRepository;
use App\Repository\StateRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;
    private EntityRepository $campusRepository;
    private EntityRepository $cityRepository;
    private EntityRepository $locationRepository;
    private EntityRepository $stateRepository;
    private EntityRepository $userRepository;
    private EntityRepository $activityRepository;

    private const NB_CITIES = 20;
    private const NB_LOCATIONS = 30;
    private const NB_USERS = 50;
    private const NB_ACTIVITIES = 50;


    public function __construct(UserPasswordEncoderInterface $encoder, CampusRepository $campusRepository,
                                CityRepository $cityRepository, LocationRepository $locationRepository,
                                StateRepository $stateRepository, UserRepository $userRepository,
                                ActivityRepository $activityRepository){
        $this->encoder = $encoder;
        $this->campusRepository = $campusRepository;
        $this->cityRepository = $cityRepository;
        $this->locationRepository = $locationRepository;
        $this->stateRepository = $stateRepository;
        $this->userRepository = $userRepository;
        $this->activityRepository = $activityRepository;
    }

    public function load(ObjectManager $manager)
    {
        $this->fixCampuses($manager);
        $this->fixCities($manager);
        $this->fixlocations($manager);
        $this->fixStates($manager);
        $this->fixTestUser($manager);
        $this->fixUsers($manager);
        $this->fixActivities($manager);
        $this->fixUsersActivities($manager);

    }

    private function fixCampuses(ObjectManager $manager){
        $campus1 = new Campus();
        $campus1->setName('Saint-Herblain');
        $campus2 = new Campus();
        $campus2->setName('Chartres de Bretagne');
        $campus3 = new Campus();
        $campus3->setName('La Roche sur Yon');

        $manager->persist($campus1);
        $manager->persist($campus2);
        $manager->persist($campus3);

        $manager->flush();
    }

    private function fixCities(ObjectManager $manager){
        $generator = Factory::create('fr_FR');

        for ($i = 0; $i < self::NB_CITIES; $i++){
            $city = new City();
            $city->setName($generator->city)
                ->setPostalCode($generator->randomNumber(5));
            $manager->persist($city);
        }
        $manager->flush();
    }

    private function fixlocations(ObjectManager $manager)
    {
        $generator = Factory::create('fr_FR');
        $cities = $this->cityRepository->findAll();

        for ($i = 0; $i <= self::NB_LOCATIONS; $i++) {
            $city = $cities[$generator->numberBetween(0, count($cities)-1)];
            $location = new Location();
            $location->setName('lieu n°'.$i)
                ->setStreet($generator->streetAddress)
                ->setLatitude($generator->randomFloat(6,0, 9))
                ->setLongitude($generator->randomFloat(6,0, 9))
                ->setCity($city);
            $manager->persist($location);

            $city->addLocation($location);
            $manager->persist($city);
        }
        $manager->flush();
    }

    private function fixStates(ObjectManager $manager){
        for ($i = 0; $i < 6; $i++){
            $state = new State();
            $state->setLabel(State::TAB_LABEL[$i]);
            $manager->persist($state);
        }
        $manager->flush();
    }

    private function fixUsers(ObjectManager $manager){
        $generator = Factory::create('fr_FR');
        $campuses = $this->campusRepository->findAll();

        for ($i = 0; $i < self::NB_USERS; $i++){
            $campus = $campuses[$generator->numberBetween(0, count($campuses)-1)];
            $user = new User();
            $password = $this->encoder->encodePassword($user, 'password'.$i);

            $user->setUsername($generator->userName)
                ->setName($generator->lastName)
                ->setFirstName($generator->firstName)
                ->setPhone($generator->randomNumber(5) . $generator->randomNumber(5))
                ->setEmail($generator->email)
                ->setRoles(['ROLE_USER'])
                ->setPassword($password)
                ->setCampus($campus)
                ->setActive(true)
                ->setAdmin(false);
            $manager->persist($user);

            $campus->addUser($user);
            $manager->persist($campus);
        }
        $manager->flush();
    }

    private function fixActivities(ObjectManager $manager){
        $generator = Factory::create('fr_FR');
        $campuses = $this->campusRepository->findAll();
        $states = $this->stateRepository->findAll();
        $users = $this->userRepository->findAll();
        $locations = $this->locationRepository->findAll();
        for ($i = 0; $i < self::NB_ACTIVITIES; $i++){
            $campus = $campuses[$generator->numberBetween(0, count($campuses)-1)];
            $userOwner = $users[$generator->numberBetween(0, count($users)-1)];
            $location = $locations[$generator->numberBetween(0, count($locations)-1)];
            $startDate = $generator->dateTimeBetween('2020-09-01','2022-07-04');
            $limitDate = $generator->dateTimeBetween('2020-09-01', $startDate);
            $activity = new Activity();
            $state = $states[$generator->numberBetween(0, count($states)-1)];
            $activity->setName('Sortie n°'.$i)
                ->setStartDateTime($startDate)
                ->setDuration($generator->numberBetween(1, 12))
                ->setInscriptionLimitDate($limitDate)
                ->setActivityInfo($generator->realText(120))
                ->setMaxInscriptionsNb($generator->numberBetween(1, 30))
                ->setCampus($campus)
                ->setUserOwner($userOwner)
                ->setState($state)
                ->setLocation($location);
//            for ($j = 0; $j < $generator->numberBetween(0, 30); $j++){
//                $activity->addUser($users[$generator->numberBetween(0, count($users))]);
//            }
            $manager->persist($activity);
            $userOwner->addActivitiesOwned($activity);
            $manager->persist($userOwner);
            $campus->addActivity($activity);
            $manager->persist($campus);
            $location->addActivity($activity);
            $manager->persist($location);
            $state->addActivity($activity);
            $manager->persist($state);
        }
        $manager->flush();
    }

    private function fixUsersActivities(ObjectManager $manager)
    {
        $generator = Factory::create('fr_FR');
        $activities = $this->activityRepository->findAll();
        $users = $this->userRepository->findAll();

        for ($i = 0; $i < count($activities); $i++){
            $activity = $activities[$i];
            $endLoop = $activity->getMaxInscriptionsNb() - $generator->numberBetween(0, $activity->getMaxInscriptionsNb());
            for ($j = 0; $j < $endLoop; $j++) {
                $user = $users[$generator->numberBetween(0, count($users)-1)];
                $activity->addUser($user);
                $user->addActivity($activity);
                $manager->persist($user);
            }
            $manager->persist($activity);
        }
        $manager->flush();
    }

    private function fixTestUser(ObjectManager $manager){
        $campus = $this->campusRepository->findOneBy(['name' => 'Chartres de Bretagne']);
        $martin = new User();
        $passwordMartin = $this->encoder->encodePassword($martin, 'martin');
        $martin->setUsername('martin')
            ->setName('Fléchard')
            ->setFirstName('Martin')
            ->setPhone('0614586523')
            ->setEmail('martin.flechard2021@campus-eni.fr')
            ->setRoles(['ROLE_USER'])
            ->setPassword($passwordMartin)
            ->setCampus($campus)
            ->setActive(true)
            ->setAdmin(false);
        $antoine = new User();
        $passwordAntoine = $this->encoder->encodePassword($martin, 'password');
        $antoine->setUsername('antoine')
            ->setName('Morfouesse')
            ->setFirstName('Antoine')
            ->setPhone('0647586932')
            ->setEmail('antoine.morfouesse2021@campus-eni.fr')
            ->setRoles(['ROLE_USER'])
            ->setPassword($passwordAntoine)
            ->setCampus($campus)
            ->setActive(true)
            ->setAdmin(false);
        $campus->addUser($martin);
        $manager->persist($martin);
        $campus->addUser($antoine);
        $manager->persist($antoine);
        $manager->persist($campus);
        $manager->flush();
    }

}
