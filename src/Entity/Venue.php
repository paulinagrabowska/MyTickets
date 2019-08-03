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
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     */
    private $name;

    /**
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
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank
     * @Assert\GreaterThan(
     *     value = 0
     * )
     */
    private $streetnumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Concert", mappedBy="venue")
     *
     * @Assert\NotBlank
     */
    private $concerts;

    public function __construct()
    {
        $this->concerts = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getStreetnumber(): ?int
    {
        return $this->streetnumber;
    }

    public function setStreetnumber(int $streetnumber): self
    {
        $this->streetnumber = $streetnumber;

        return $this;
    }

    public function getConcerts(): Collection
    {
        return $this->concerts;
    }

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

    public function addConcert(Concert $concert): self
    {
        if (!$this->concerts->contains($concert)) {
            $this->concerts[] = $concert;
            $concert->setVenue($this);
        }

        return $this;
    }

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
}
