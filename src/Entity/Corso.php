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
     * @ORM\ManyToMany(targetEntity=CorsoDiStudio::class, mappedBy="corsi")
     */
    private $corsiDiStudi;

    public function __construct()
    {
        $this->corsiDiStudi = new ArrayCollection();
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
     * @return Collection|CorsoDiStudio[]
     */
    public function getCorsiDiStudi(): Collection
    {
        return $this->corsiDiStudi;
    }

    public function addCorsiDiStudi(CorsoDiStudio $corsiDiStudi): self
    {
        if (!$this->corsiDiStudi->contains($corsiDiStudi)) {
            $this->corsiDiStudi[] = $corsiDiStudi;
            $corsiDiStudi->addCorsi($this);
        }

        return $this;
    }

    public function removeCorsiDiStudi(CorsoDiStudio $corsiDiStudi): self
    {
        if ($this->corsiDiStudi->removeElement($corsiDiStudi)) {
            $corsiDiStudi->removeCorsi($this);
        }

        return $this;
    }
}
