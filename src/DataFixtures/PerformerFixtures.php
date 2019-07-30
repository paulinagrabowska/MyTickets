<?php
/**
 * Performer fixture.
 */
namespace App\DataFixtures;

use App\Entity\Performer;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CategoryFixtures.
 */
class PerformerFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {

        $this->createMany(10, 'performers', function ($i) {
            $performer = new Performer();
            $performer->setFirstname($this->faker->firstName);
            $performer->setLastname($this->faker->lastName);
            $performer->setInfo($this->faker->paragraph);
            $performer->setMusicgenre($this->faker->word);
            $performer->setStagename($this->faker->company);
            return $performer;
        });

        $manager->flush();
    }
}