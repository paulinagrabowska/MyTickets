<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConcertRepository")
 * @ORM\Table(name="concerts")
 */
class Concert
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
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Name.
     * @var string
     * @ORM\Column(type="string", length=45)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="5",
     *     max="45",
     * )
     */
    private $name;

    /**
     * Concert information
     *
     * @var string
     * @ORM\Column(type="string", length=512)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="5",
     *     max="512",
     * )
     */
    private $info;

    /**
     * Concert date.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    private $date;

    /**
     * Concert reservations limit.
     *
     * @var int
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank
     * @Assert\GreaterThan(
     *     value = 10
     * )
     */
    private $reservation_limit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Performer", inversedBy="concerts", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $performer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Venue", inversedBy="concerts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $venue;

    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Tag",
     *     inversedBy="concerts",
     *     orphanRemoval=true
     * )
     * @ORM\JoinTable(name="concerts_tags")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="concert")
     */
    private $reservations;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->reservations = new ArrayCollection();
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
     * Getter for concert name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for concert name.
     *
     * @param string $name
     * @return Concert
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for concert info.
     *
     * @return string|null
     */
    public function getInfo(): ?string
    {
        return $this->info;
    }

    /**
     * Setter for concert info.
     *
     * @param string $info
     * @return Concert
     */

    public function setInfo(string $info): self
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Getter for concert date.
     *
     * @return \DateTimeInterface|null
     *
     * @Assert\DateTime
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Setter for concert date.
     *
     * @param \DateTimeInterface $date
     * @return Concert
     */

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Getter for concert reservations limit.
     *
     * @return int
     */
    public function getReservationLimit(): int
    {
        return $this->reservation_limit - $this->reservations->count();
    }

    /**
     * Setetr for concert reservations limit.
     *
     * @param int $reservation_limit
     * @return Concert
     */
    public function setReservationLimit(int $reservation_limit): self
    {
        $this->reservation_limit = $reservation_limit;

        return $this;
    }

    /**
     * Getter for Performer.
     *
     * @return Performer|null
     */

    public function getPerformer(): ?Performer
    {
        return $this->performer;
    }

    /**
     * Setter for Performer.
     *
     * @param Performer|null $performer
     * @return Concert
     */
    public function setPerformer(?Performer $performer): self
    {
        $this->performer = $performer;

        return $this;
    }

    public function getVenue(): ?Venue
    {
        return $this->venue;
    }

    public function setVenue(?Venue $venue): self
    {
        $this->venue = $venue;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setConcert($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getConcert() === $this) {
                $reservation->setConcert(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
