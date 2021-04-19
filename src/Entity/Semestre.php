<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SemestreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=SemestreRepository::class)
 */
class Semestre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=31)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=AnnoAccademico::class, inversedBy="semestres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annoAccademico;

    /**
     * @ORM\Column(type="date")
     */
    private $startsAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endsAt;

    /**
     * @ORM\OneToMany(targetEntity=Insegnamento::class, mappedBy="semestre")
     */
    private $insegnamento;

    public function __construct()
    {
        $this->insegnamento = new ArrayCollection();
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

    public function getAnnoAccademico(): ?AnnoAccademico
    {
        return $this->annoAccademico;
    }

    public function setAnnoAccademico(?AnnoAccademico $annoAccademico): self
    {
        $this->annoAccademico = $annoAccademico;

        return $this;
    }

    public function getStartsAt(): ?\DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTimeInterface $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(\DateTimeInterface $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    /**
     * @return Collection|Insegnamento[]
     */
    public function getInsegnamento(): Collection
    {
        return $this->insegnamento;
    }

    public function addInsegnamento(Insegnamento $insegnamento): self
    {
        if (!$this->insegnamento->contains($insegnamento)) {
            $this->insegnamento[] = $insegnamento;
            $insegnamento->setSemestre($this);
        }

        return $this;
    }

    public function removeInsegnamento(Insegnamento $insegnamento): self
    {
        if ($this->insegnamento->removeElement($insegnamento)) {
            // set the owning side to null (unless already changed)
            if ($insegnamento->getSemestre() === $this) {
                $insegnamento->setSemestre(null);
            }
        }

        return $this;
    }
}
