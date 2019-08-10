<?php
/**
 * Concert fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Concert;
use App\Entity\Reservation;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ConcertFixtures.
 */
class ReservationFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Faker.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Object manager.
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $manager;


    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */

    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(20, 'reservations', function ($i) {
            $reservation = new Reservation();
            $reservation->setDate($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $reservation->setConcert($this->getRandomReference('concerts'));
            $reservation->setUser($this->getRandomReference('users'));

            return $reservation;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class,ConcertFixtures::class];
    }
}
