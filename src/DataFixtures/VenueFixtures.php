<?php
/**
 * Performer fixture.
 */
namespace App\DataFixtures;

use App\Entity\Venue;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CategoryFixtures.
 */
class VenueFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {

        $this->createMany(5, 'venues', function ($i) {
            $venue = new Venue();
            $venue->setName($this->faker->company);
            $venue->setCity($this->faker->city);
            $venue->setStreet($this->faker->streetName);
            $venue->setStreetnumber($this->faker->buildingNumber);

            return $venue;
        });

        $manager->flush();
    }
}