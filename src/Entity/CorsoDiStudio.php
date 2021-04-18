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
     * @ORM\ManyToMany(targetEntity=Corso::class, inversedBy="corsiDiStudi")
     */
    private $corsi;

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
        }

        return $this;
    }

    public function removeCorsi(Corso $corsi): self
    {
        $this->corsi->removeElement($corsi);

        return $this;
    }
}
