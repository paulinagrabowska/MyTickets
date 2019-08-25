<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VenueRepository")
 * @ORM\Table(name="venues")
 */
class Venue
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
     * Name.
     *
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     */
    private $name;

    /**
     * City.
     *
     * @ORM\Column(type="string", length=30)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="30",
     * )
     */
    private $city;

    /**
     * Street.
     *
     * @ORM\Column(type="string", length=45)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     */
    private $street;

    /**
     * Street number.
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank
     * @Assert\GreaterThan(
     *     value = 0
     * )
     */
    private $streetnumber;

    /**
     * Concerts.
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Concert", mappedBy="venue")
     *
     * @Assert\NotBlank
     */
    private $concerts;

    public function __construct()
    {
        $this->concerts = new ArrayCollection();
    }

    /**
     * Getter for venue id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for venue name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for venue name.
     *
     * @param string $name
     * @return Venue
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for venue city.
     *
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Setter for venue city.
     *
     * @param string $city
     * @return Venue
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Getter for venue street.
     *
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * Setter for venue street.
     *
     * @param string $street
     * @return Venue
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Getter for venue street number.
     *
     * @return int|null
     */
    public function getStreetnumber(): ?int
    {
        return $this->streetnumber;
    }

    /**
     * Setter for venue street number.
     *
     * @param int $streetnumber
     * @return Venue
     */
    public function setStreetnumber(int $streetnumber): self
    {
        $this->streetnumber = $streetnumber;

        return $this;
    }

    /**
     * Getter for concerts.
     *
     * @return Collection
     */
    public function getConcerts(): Collection
    {
        return $this->concerts;
    }

    /**
     * Setter for concerts.
     *
     * @param Concert|null $concerts
     * @return Venue
     */
    public function setConcerts(?Concert $concerts): self
    {
        $this->concerts = $concerts;

        // set (or unset) the owning side of the relation if necessary
        $newVenue = $concerts === null ? null : $this;
        if ($newVenue !== $concerts->getVenue()) {
            $concerts->setVenue($newVenue);
        }

        return $this;
    }

    /**
     * Add a concert.
     *
     * @param Concert $concert
     * @return Venue
     */
    public function addConcert(Concert $concert): self
    {
        if (!$this->concerts->contains($concert)) {
            $this->concerts[] = $concert;
            $concert->setVenue($this);
        }

        return $this;
    }

    /**
     * Remove a concert.
     *
     * @param Concert $concert
     * @return Venue
     */
    public function removeConcert(Concert $concert): self
    {
        if ($this->concerts->contains($concert)) {
            $this->concerts->removeElement($concert);
            // set the owning side to null (unless already changed)
            if ($concert->getVenue() === $this) {
                $concert->setVenue(null);
            }
        }

        return $this;
    }

    /**
     * To string method.
     *
     * @return string|null
     */
    public function __toString()
    {
        return $this->getName();
    }
}
