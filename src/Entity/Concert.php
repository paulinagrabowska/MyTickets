<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $name;

    /**
     * Concert information
     *
     * @var string
     * @ORM\Column(type="string", length=512)
     */
    private $info;

    /**
     * Concert date.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * Concert reservations limit.
     *
     * @var int
     * @ORM\Column(type="integer")
     */
    private $reservation_limit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Performer", inversedBy="concerts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $performer;

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
     * @return int|null
     */
    public function getReservationLimit(): ?int
    {
        return $this->reservation_limit;
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
}
