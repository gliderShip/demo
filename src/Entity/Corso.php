<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CorsoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CorsoRepository::class)
 */
class Corso
{
    /**
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
     * @ORM\ManyToOne(targetEntity=Insegnamento::class, inversedBy="corso")
     * @ORM\JoinColumn(nullable=false)
     */
    private $insegnamento;

    /**
     * @ORM\OneToMany(targetEntity=ModuloCorso::class, mappedBy="corso", fetch="EAGER")
     */
    private $moduli;

    /**
     * @ORM\ManyToOne(targetEntity=Teacher::class, inversedBy="corsi")
     * @ORM\JoinColumn(nullable=false)
     */
    private $titolare;

    /**
     * @ORM\ManyToOne(targetEntity=CorsoDiStudio::class, inversedBy="corsi")
     * @ORM\JoinColumn(nullable=false)
     */
    private $corsoDiStudio;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mandatory;

    public function __construct()
    {
        $this->moduli = new ArrayCollection();
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

    public function getInsegnamento(): ?Insegnamento
    {
        return $this->insegnamento;
    }

    public function setInsegnamento(?Insegnamento $insegnamento): self
    {
        $this->insegnamento = $insegnamento;

        return $this;
    }

    /**
     * @return Collection|ModuloCorso[]
     */
    public function getModuli(): Collection
    {
        return $this->moduli;
    }

    public function addModuli(ModuloCorso $moduli): self
    {
        if (!$this->moduli->contains($moduli)) {
            $this->moduli[] = $moduli;
            $moduli->setCorso($this);
        }

        return $this;
    }

    public function removeModuli(ModuloCorso $moduli): self
    {
        if ($this->moduli->removeElement($moduli)) {
            // set the owning side to null (unless already changed)
            if ($moduli->getCorso() === $this) {
                $moduli->setCorso(null);
            }
        }

        return $this;
    }

    public function getCredits(): ?int
    {
        $credits = 0;
        foreach ($this->getModuli() as $modulo) {
            $credits += $modulo->getCredits();
        }

        return $credits;
    }

    public function setCredits(int $credits): self
    {
        $this->credits = $credits;

        return $this;
    }

    public function getTitolare(): ?Teacher
    {
        return $this->titolare;
    }

    /**
     * @param Teacher|null $titolare
     * @return $this
     */
    public function setTitolare(?Teacher $titolare): self
    {
        $this->titolare = $titolare;

        return $this;
    }

    public function getCorsoDiStudio(): ?CorsoDiStudio
    {
        return $this->corsoDiStudio;
    }

    public function setCorsoDiStudio(?CorsoDiStudio $corsoDiStudio): self
    {
        $this->corsoDiStudio = $corsoDiStudio;

        return $this;
    }

    public function getMandatory(): ?bool
    {
        return $this->mandatory;
    }

    public function setMandatory(bool $mandatory): self
    {
        $this->mandatory = $mandatory;

        return $this;
    }

}
