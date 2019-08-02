<?php
/**
 * Concert fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Concert;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ConcertFixtures.
 */
class ConcertFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
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
        $this->createMany(20, 'concerts', function ($i) {
            $concert = new Concert();
            $concert->setName($this->faker->sentence);
            $concert->setInfo($this->faker->paragraph);
            $concert->setDate($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $concert->setReservationLimit($this->faker->biasedNumberBetween($min = 10, $max = 10000, $function = 'sqrt'));
            $concert->setPerformer($this->getRandomReference('performers'));
            $concert->setVenue($this->getRandomReference('venues'));

            return $concert;
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
        return [PerformerFixtures::class,PerformerFixtures::class, VenueFixtures::class];
    }
}
