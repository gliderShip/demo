<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
class Teacher
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
     * @ORM\OneToMany(targetEntity=Corso::class, mappedBy="titolare")
     */
    private $corsi;

    /**
     * @ORM\OneToMany(targetEntity=ModuloInsegnamento::class, mappedBy="teacher")
     */
    private $moduliInsegnamento;

    public function __construct()
    {
        $this->corsi = new ArrayCollection();
        $this->moduliInsegnamento = new ArrayCollection();
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
            $corsi->setTeacher($this);
        }

        return $this;
    }

    public function removeCorsi(Corso $corsi): self
    {
        if ($this->corsi->removeElement($corsi)) {
            // set the owning side to null (unless already changed)
            if ($corsi->getTeacher() === $this) {
                $corsi->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ModuloInsegnamento[]
     */
    public function getModuliInsegnamento(): Collection
    {
        return $this->moduliInsegnamento;
    }

    public function addModuliInsegnamento(ModuloInsegnamento $moduliInsegnamento): self
    {
        if (!$this->moduliInsegnamento->contains($moduliInsegnamento)) {
            $this->moduliInsegnamento[] = $moduliInsegnamento;
            $moduliInsegnamento->setTeacher($this);
        }

        return $this;
    }

    public function removeModuliInsegnamento(ModuloInsegnamento $moduliInsegnamento): self
    {
        if ($this->moduliInsegnamento->removeElement($moduliInsegnamento)) {
            // set the owning side to null (unless already changed)
            if ($moduliInsegnamento->getTeacher() === $this) {
                $moduliInsegnamento->setTeacher(null);
            }
        }

        return $this;
    }
}
