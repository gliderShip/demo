<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\InsegnamentoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=InsegnamentoRepository::class)
 */
class Insegnamento
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Corso::class, mappedBy="insegnamento")
     */
    private $corso;

    /**
     * @ORM\Column(type="string", length=127)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=ModuloInsegnamento::class, mappedBy="insegnamento", fetch="EAGER")
     */
    private $moduli;

    public function __construct()
    {
        $this->corso = new ArrayCollection();
        $this->moduli = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Corso[]
     */
    public function getCorso(): Collection
    {
        return $this->corso;
    }

    public function addCorso(Corso $corso): self
    {
        if (!$this->corso->contains($corso)) {
            $this->corso[] = $corso;
            $corso->setInsegnamento($this);
        }

        return $this;
    }

    public function removeCorso(Corso $corso): self
    {
        if ($this->corso->removeElement($corso)) {
            // set the owning side to null (unless already changed)
            if ($corso->getInsegnamento() === $this) {
                $corso->setInsegnamento(null);
            }
        }

        return $this;
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
     * @return Collection|ModuloInsegnamento[]
     */
    public function getModuli(): Collection
    {
        return $this->moduli;
    }

    public function addModuli(ModuloInsegnamento $moduli): self
    {
        if (!$this->moduli->contains($moduli)) {
            $this->moduli[] = $moduli;
            $moduli->setInsegnamento($this);
        }

        return $this;
    }

    public function removeModuli(ModuloInsegnamento $moduli): self
    {
        if ($this->moduli->removeElement($moduli)) {
            // set the owning side to null (unless already changed)
            if ($moduli->getInsegnamento() === $this) {
                $moduli->setInsegnamento(null);
            }
        }

        return $this;
    }
}
