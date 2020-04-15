<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VilleRepository")
 */
class Ville
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $zip;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lieu", mappedBy="ville")
     */
    private $lieu;

    public function __construct()
    {
        $this->lieu = new ArrayCollection();
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

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return Collection|Lieu[]
     */
    public function getLieu(): Collection
    {
        return $this->lieu;
    }

    public function addOneToMany(Lieu $oneToMany): self
    {
        if (!$this->lieu->contains($oneToMany)) {
            $this->lieu[] = $oneToMany;
            $oneToMany->setVille($this);
        }

        return $this;
    }

    public function removeOneToMany(Lieu $oneToMany): self
    {
        if ($this->lieu->contains($oneToMany)) {
            $this->lieu->removeElement($oneToMany);
            // set the owning side to null (unless already changed)
            if ($oneToMany->getVille() === $this) {
                $oneToMany->setVille(null);
            }
        }

        return $this;
    }
}
