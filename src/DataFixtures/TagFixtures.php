<?php
/**
 * Performer fixture.
 */
namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {

        $this->createMany(10, 'tags', function ($i) {
            $tag = new Tag();
            $tag->setTitle($this->faker->word);

            return $tag;
        });

        $manager->flush();
    }
}