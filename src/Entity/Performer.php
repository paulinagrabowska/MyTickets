<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PerformerRepository")
 * @ORM\Table(name="performers")
 */
class Performer
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
     * First name.
     *
     * @var string
     * @ORM\Column(type="string", length=30)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="30",
     * )
     */
    private $firstname;

    /**
     * Last name.
     *
     * @var string
     * @ORM\Column(type="string", length=30)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="30",
     * )
     */
    private $lastname;

    /**
     * Stage name.
     *
     * @var string
     * @ORM\Column(type="string", length=30, nullable=true)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="30",
     * )
     */
    private $stagename;

    /**
     * Information.
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
     * Music genre.
     *
     * @var string
     * @ORM\Column(type="string", length=30)
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="30",
     * )
     */
    private $musicgenre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Concert", mappedBy="performer")
     *
     * @Assert\NotBlank
     */
    private $concerts;

    /**
     * @ORM\Column(type="string", length=30)
     * @Gedmo\Slug(fields={"lastname"})
     *
     * @Assert\Length(
     *     min="3",
     *     max="30",
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private $image;


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
     * Getter for Firstname.
     *
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * Setter for Firstname.
     *
     * @param string $firstname
     * @return Performer
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Getter for Lastname.
     *
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * Setter for Lastname.
     *
     * @param string $lastname
     * @return Performer
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Getter fir Stagename.
     *
     * @return string|null
     */
    public function getStagename(): ?string
    {
        return $this->stagename;
    }

    /**
     * Setter for Stagename.
     *
     * @param string|null $stagename
     * @return Performer
     */
    public function setStagename(?string $stagename): self
    {
        $this->stagename = $stagename;

        return $this;
    }

    /**
     * Getter for info.
     *
     * @return string|null
     */
    public function getInfo(): ?string
    {
        return $this->info;
    }

    /**
     * Setter for info.
     *
     * @param string $info
     * @return Performer
     */
    public function setInfo(string $info): self
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Getter for Musicgenre.
     *
     * @return string|null
     */

    public function getMusicgenre(): ?string
    {
        return $this->musicgenre;
    }

    /**
     * Setter for Musicgenre.
     *
     * @param string $musicgenre
     * @return Performer\
     */

    public function setMusicgenre(string $musicgenre): self
    {
        $this->musicgenre = $musicgenre;

        return $this;
    }

    /**
     * @return Collection|Concert[]
     */
    public function getConcerts(): Collection
    {
        return $this->concerts;
    }

    public function addConcert(Concert $concert): self
    {
        if (!$this->concerts->contains($concert)) {
            $this->concerts[] = $concert;
            $concert->setPerformer($this);
        }

        return $this;
    }

    public function removeConcert(Concert $concert): self
    {
        if ($this->concerts->contains($concert)) {
            $this->concerts->removeElement($concert);
            // set the owning side to null (unless already changed)
            if ($concert->getPerformer() === $this) {
                $concert->setPerformer(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function __toString()
    {
        return $this->stagename;
    }

}
