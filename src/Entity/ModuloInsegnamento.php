<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ModuloInsegnamentoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ModuloInsegnamentoRepository::class)
 */
class ModuloInsegnamento
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
     * @ORM\ManyToOne(targetEntity=Insegnamento::class, inversedBy="moduli")
     * @ORM\JoinColumn(nullable=false)
     */
    private $insegnamento;

    /**
     * @ORM\OneToMany(targetEntity=ModuloCorso::class, mappedBy="moduloInsegnamento")
     */
    private $moduliCorsi;

    /**
     * @ORM\ManyToOne(targetEntity=Teacher::class, inversedBy="moduliInsegnamento")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teacher;

    public function __construct()
    {
        $this->moduliCorsi = new ArrayCollection();
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
    public function getModuliCorsi(): Collection
    {
        return $this->moduliCorsi;
    }

    public function addModuliCorsi(ModuloCorso $moduliCorsi): self
    {
        if (!$this->moduliCorsi->contains($moduliCorsi)) {
            $this->moduliCorsi[] = $moduliCorsi;
            $moduliCorsi->setModuloInsegnamento($this);
        }

        return $this;
    }

    public function removeModuliCorsi(ModuloCorso $moduliCorsi): self
    {
        if ($this->moduliCorsi->removeElement($moduliCorsi)) {
            // set the owning side to null (unless already changed)
            if ($moduliCorsi->getModuloInsegnamento() === $this) {
                $moduliCorsi->setModuloInsegnamento(null);
            }
        }

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }
}
