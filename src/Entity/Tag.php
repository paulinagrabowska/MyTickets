<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(name="tags")
 */
class Tag
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     *
     * @constant int NUMBER_OF_ITEMS
     */
    const NUMBER_OF_ITEMS = 6;

    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Tag Title.
     *
     * @ORM\Column(type="string", length=30)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="30",
     * )
     */
    private $title;

    /**
     * Concerts.
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Concert", mappedBy="tags")
     */

    private $concerts;

    public function __construct()
    {
        $this->concerts = new ArrayCollection();
    }

    /**
     * Getter for id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for title.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string $title
     * @return Tag
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter for concerts.
     *
     * @return Collection|Concert[]
     */
    public function getConcerts(): Collection
    {
        return $this->concerts;
    }

    /**
     * Add concert.
     *
     * @param Concert $concert
     * @return Tag
     */
    public function addConcert(Concert $concert): self
    {
        if (!$this->concerts->contains($concert)) {
            $this->concerts[] = $concert;
            $concert->addTag($this);
        }

        return $this;
    }

    /**
     * Remove concert.
     *
     * @param Concert $concert
     * @return Tag
     */
    public function removeConcert(Concert $concert): self
    {
        if ($this->concerts->contains($concert)) {
            $this->concerts->removeElement($concert);
            $concert->removeTag($this);
        }

        return $this;
    }

    /**
     * toString method.
     *
     * @return string|null
     */
    public function __toString()
    {
        return $this->getTitle();
    }
}
