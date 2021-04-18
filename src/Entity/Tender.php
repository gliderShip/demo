<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TenderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TenderRepository::class)
 */
class Tender
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publicationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $openDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $closeDate;

    /**
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    private $referenceNumber;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasLot;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $canceled;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $suspended;

    /**
     * @ORM\Column(type="string", length=127, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=63, nullable=true, unique=true)
     */
    private $notificationNumber;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     */
    private $cpvCode;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="tender", orphanRemoval=true)
     */
    private $tenderDocuments;

    /**
     * @ORM\ManyToOne(targetEntity=Agency::class, inversedBy="tenders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agency;

    public function __construct()
    {
        $this->tenderDocuments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getOpenDate(): ?\DateTimeInterface
    {
        return $this->openDate;
    }

    public function setOpenDate(?\DateTimeInterface $openDate): self
    {
        $this->openDate = $openDate;

        return $this;
    }

    public function getCloseDate(): ?\DateTimeInterface
    {
        return $this->closeDate;
    }

    public function setCloseDate(?\DateTimeInterface $closeDate): self
    {
        $this->closeDate = $closeDate;

        return $this;
    }

    public function getReferenceNumber(): ?string
    {
        return $this->referenceNumber;
    }

    public function setReferenceNumber(?string $referenceNumber): self
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
    }

    public function getHasLot(): ?bool
    {
        return $this->hasLot;
    }

    public function setHasLot(?bool $hasLot): self
    {
        $this->hasLot = $hasLot;

        return $this;
    }

    public function getCanceled(): ?bool
    {
        return $this->canceled;
    }

    public function setCanceled(?bool $canceled): self
    {
        $this->canceled = $canceled;

        return $this;
    }

    public function getSuspended(): ?bool
    {
        return $this->suspended;
    }

    public function setSuspended(?bool $suspended): self
    {
        $this->suspended = $suspended;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNotificationNumber(): ?string
    {
        return $this->notificationNumber;
    }

    public function setNotificationNumber(?string $notificationNumber): self
    {
        $this->notificationNumber = $notificationNumber;

        return $this;
    }

    public function getCpvCode(): ?string
    {
        return $this->cpvCode;
    }

    public function setCpvCode(?string $cpvCode): self
    {
        $this->cpvCode = $cpvCode;

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getTenderDocuments(): Collection
    {
        return $this->tenderDocuments;
    }

    public function addTenderDocument(Document $tenderDocument): self
    {
        if (!$this->tenderDocuments->contains($tenderDocument)) {
            $this->tenderDocuments[] = $tenderDocument;
            $tenderDocument->setTender($this);
        }

        return $this;
    }

    public function removeTenderDocument(Document $tenderDocument): self
    {
        if ($this->tenderDocuments->removeElement($tenderDocument)) {
            // set the owning side to null (unless already changed)
            if ($tenderDocument->getTender() === $this) {
                $tenderDocument->setTender(null);
            }
        }

        return $this;
    }

    public function getAgency(): ?Agency
    {
        return $this->agency;
    }

    public function setAgency(?Agency $agency): self
    {
        $this->agency = $agency;

        return $this;
    }
}
