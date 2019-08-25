<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 * @ORM\Table(name="reservations")
 */
class Reservation
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
     * Reservation date.
     *
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $date;

    /**
     * User.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reservations")
     */
    private $user;

    /**
     * Concert.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Concert", inversedBy="reservations")
     */
    private $concert;

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
     * Getter for date.
     *
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Setter for date.
     *
     * @param \DateTimeInterface $date
     * @return Reservation
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Getter for user.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for user.
     *
     * @param User|null $user
     * @return Reservation
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Getter for concert.
     *
     * @return Concert|null
     */
    public function getConcert(): ?Concert
    {
        return $this->concert;
    }

    /**
     * Setetr for concert.
     *
     * @param Concert|null $concert
     * @return Reservation
     */
    public function setConcert(?Concert $concert): self
    {
        $this->concert = $concert;

        return $this;
    }

}
