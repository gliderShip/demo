<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ModuloCorsoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ModuloCorsoRepository::class)
 */
class ModuloCorso
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
     * @ORM\Column(type="smallint")
     */
    private $credits;

    /**
     * @ORM\ManyToOne(targetEntity=Corso::class, inversedBy="moduli")
     * @ORM\JoinColumn(nullable=false)
     */
    private $corso;

    /**
     * @ORM\ManyToOne(targetEntity=ModuloInsegnamento::class, inversedBy="moduliCorsi", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $moduloInsegnamento;


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

    public function getCredits(): ?int
    {
        return $this->credits;
    }

    public function setCredits(int $credits): self
    {
        $this->credits = $credits;

        return $this;
    }

    public function getCorso(): ?Corso
    {
        return $this->corso;
    }

    public function setCorso(?Corso $corso): self
    {
        $this->corso = $corso;

        return $this;
    }

    public function getModuloInsegnamento(): ?ModuloInsegnamento
    {
        return $this->moduloInsegnamento;
    }

    public function setModuloInsegnamento(?ModuloInsegnamento $moduloInsegnamento): self
    {
        $this->moduloInsegnamento = $moduloInsegnamento;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->getModuloInsegnamento()->getTeacher();
    }
    
}
