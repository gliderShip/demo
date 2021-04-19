<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CorsoDiStudioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CorsoDiStudioRepository::class)
 */
class CorsoDiStudio
{
    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=127)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=Corso::class, mappedBy="corsoDiStudio", orphanRemoval=true)
     */
    private $corsi;

    /**
     * @ORM\ManyToOne(targetEntity=AnnoAccademico::class, inversedBy="corsiDiStudio")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annoAccademico;


    public function __construct()
    {
        $this->corsi = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Corso[]
     */
    public function getCorsi(): Collection
    {
        return $this->corsi;
    }

    public function addCorsi(Corso $corsi): self
    {
        if (!$this->corsi->contains($corsi)) {
            $this->corsi[] = $corsi;
            $corsi->setCorsoDiStudio($this);
        }

        return $this;
    }

    public function removeCorsi(Corso $corsi): self
    {
        if ($this->corsi->removeElement($corsi)) {
            // set the owning side to null (unless already changed)
            if ($corsi->getCorsoDiStudio() === $this) {
                $corsi->setCorsoDiStudio(null);
            }
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getMandatoryCoursesCredits(): int
    {
        $credits = 0;
        foreach ($this->getCorsi()as $corso) {
            if($corso->getMandatory()){
                $credits += $corso->getCredits();
            }

        }

        return $credits;
    }

    /**
     * @return int
     */
    public function getOptionalCoursesCredits(): int
    {
        $credits = 0;
        foreach ($this->getCorsi()as $corso) {
            if(!$corso->getMandatory()){
                $credits += $corso->getCredits();
            }

        }

        return $credits;
    }

    /**
     * @return int
     */
    public function getTotalCoursesCredits(): int
    {
        $credits = 0;
        foreach ($this->getCorsi()as $corso) {
                $credits += $corso->getCredits();
        }

        return $credits;
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


}
