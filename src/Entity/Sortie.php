<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SortieRepository")
 */
class Sortie
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
     * @ORM\Column(type="datetime")

     */
    private $dateTimeStart;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $deadlineRegistration;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxNumberRegistration;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sortiesOrganisees")
 * @ORM\JoinColumn(name="organisateur_id", referencedColumnName="id", onDelete="SET NULL")
 */
    private $organisateur;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Etat", inversedBy="sortiesPrevues")
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lieu", inversedBy="sorties")
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participations", mappedBy="sortie", cascade={"remove"})
     */
    private $participations;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $privee;

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
    if ($this->dateTimeStart < $this->deadlineRegistration) {
            $context->buildViolation('La date de cloture des inscriptions ne peut pas être inférieure à la date de début de la sortie!')
                ->atPath('deadlineRegistration')
                ->addViolation();
        }
    }

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $annulation;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
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

    public function getDateTimeStart(): ?\DateTimeInterface
    {
        return $this->dateTimeStart;
    }

    public function setDateTimeStart(\DateTimeInterface $dateTimeStart): self
    {
        $this->dateTimeStart = $dateTimeStart;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDeadlineRegistration(): ?\DateTimeInterface
    {
        return $this->deadlineRegistration;
    }

    public function setDeadlineRegistration(\DateTimeInterface $deadlineRegistration): self
    {
        $this->deadlineRegistration = $deadlineRegistration;

        return $this;
    }

    public function getMaxNumberRegistration(): ?int
    {
        return $this->maxNumberRegistration;
    }

    public function setMaxNumberRegistration(int $maxNumberRegistration): self
    {
        $this->maxNumberRegistration = $maxNumberRegistration;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrganisateur()
    {
        return $this->organisateur;
    }


    /**
     * @param mixed $organisateur
     */
    public function setOrganisateur($organisateur): void
    {
        $this->organisateur = $organisateur;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    /**
     * @param  $lieu
     */
    public function setLieu(Lieu $lieu): void
    {
        $this->lieu = $lieu;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getParticipations(): ArrayCollection {
        return $this->participations;
    }

    /**
     * @param ArrayCollection $participations
     */
    public function setParticipations(ArrayCollection $participations): void {
        $this->participations = $participations;
    }

    public function getPrivee(): ?bool
    {
        return $this->privee;
    }

    public function setPrivee(?bool $privee): self
    {
        $this->privee = $privee;

        return $this;
    }

    public function getAnnulation(): ?string
    {
        return $this->annulation;
    }

    public function setAnnulation(?string $annulation): self
    {
        $this->annulation = $annulation;

        return $this;
    }



}
