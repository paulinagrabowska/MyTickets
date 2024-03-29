<?php
/**
 * Tags data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer.
 */
class TagsDataTransformer implements DataTransformerInterface
{
    /**
     * Tag repository.
     *
     * @var \App\Repository\TagRepository|null
     */
    private $repository = null;

    /**
     * TagsDataTransformer constructor.
     *
     * @param \App\Repository\TagRepository $repository Tag repository
     */
    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    /*Przetworzenie tagów z encji, na wartosci tekstowe w formularzu oddzielone przecinkami
    - działa ona podczas wypełniania formularza danymi pochodzącymi z encji.*/

    /**
     * Transform array of tags to string of names.
     *
     * @param \Doctrine\Common\Collections\Collection $tags Tags entity collection
     *
     * @return string Result
     */
    public function transform($tags): string
    {
        if (null == $tags) {
            return '';
        }

        $tagNames = [];

        foreach ($tags as $tag) {
            $tagNames[] = $tag->getTitle();
        }

        return implode(',', $tagNames);
    }

//    Przetworzenie tagów z formularza na obiekty
//    - działa podczas uzupełniania encji Task danymi pochodzącymi z formularza
//    - pobieramy z bazy danych tag na podstawie jego nazwy,
//    - jeżeli tag istnieje dodajemy go do kolekcji,
//    jeżeli nie dodajemy nowy tag do bazy danych, a następnie dodajemy go do kolekcji.
    /**
     * Transform string of tag names into array of Tag entities.
     *
     * @param string $value String of tag names
     *
     * @return array Result
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($value): array
    {
        $tagTitles = explode(',', $value);

        $tags = [];

        foreach ($tagTitles as $tagTitle) {
            //trim - wycina białe znaki
            if ('' !== trim($tagTitle)) {
                $tag = $this->repository->findOneByTitle(strtolower($tagTitle));
                if (null == $tag) {
                    $tag = new Tag();
                    $tag->setTitle($tagTitle);
                    $this->repository->save($tag);
                }
                $tags[] = $tag;
            }
        }

        return $tags;
    }
}