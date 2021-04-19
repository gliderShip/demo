<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AnnoAccademicoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AnnoAccademicoRepository::class)
 */
class AnnoAccademico
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $year;

    /**
     * @ORM\Column(type="date")
     */
    private $startsAt;

    /**
     * @ORM\Column(type="date")
     */
    private $endsAt;

    /**
     * @ORM\OneToMany(targetEntity=CorsoDiStudio::class, mappedBy="annoAccademico")
     */
    private $corsiDiStudio;

    /**
     * @ORM\OneToMany(targetEntity=Semestre::class, mappedBy="annoAccademico")
     */
    private $semestres;

    public function __construct()
    {
        $this->corsiDiStudio = new ArrayCollection();
        $this->semestres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

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
     * @return Collection|CorsoDiStudio[]
     */
    public function getCorsiDiStudio(): Collection
    {
        return $this->corsiDiStudio;
    }

    public function addCorsiDiStudio(CorsoDiStudio $corsiDiStudio): self
    {
        if (!$this->corsiDiStudio->contains($corsiDiStudio)) {
            $this->corsiDiStudio[] = $corsiDiStudio;
            $corsiDiStudio->setAnnoAccademico($this);
        }

        return $this;
    }

    public function removeCorsiDiStudio(CorsoDiStudio $corsiDiStudio): self
    {
        if ($this->corsiDiStudio->removeElement($corsiDiStudio)) {
            // set the owning side to null (unless already changed)
            if ($corsiDiStudio->getAnnoAccademico() === $this) {
                $corsiDiStudio->setAnnoAccademico(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Semestre[]
     */
    public function getSemestres(): Collection
    {
        return $this->semestres;
    }

    public function addSemestre(Semestre $semestre): self
    {
        if (!$this->semestres->contains($semestre)) {
            $this->semestres[] = $semestre;
            $semestre->setAnnoAccademico($this);
        }

        return $this;
    }

    public function removeSemestre(Semestre $semestre): self
    {
        if ($this->semestres->removeElement($semestre)) {
            // set the owning side to null (unless already changed)
            if ($semestre->getAnnoAccademico() === $this) {
                $semestre->setAnnoAccademico(null);
            }
        }

        return $this;
    }
}
