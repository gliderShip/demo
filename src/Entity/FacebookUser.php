<?php

namespace App\Entity;

use App\Repository\FacebookUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FaceBookUsersRepository::class)
 */
class FacebookUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $countryLeak;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $mobile;

    /**
     * @ORM\Column(type="bigint")
     */
    private $facebookId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $sex;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currentDistrict;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currentCountry;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currentState;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hometownDistrict;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hometownCountry;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hometownState;

    /**
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    private $relationshipStatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $workCompany;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date10;

    /**
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookCurrentAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookHometownAddress;

    /**
     * @ORM\Column(type="string", length=63, nullable=true)
     */
    private $facebookBirthDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryLeak(): ?string
    {
        return $this->countryLeak;
    }

    public function setCountryLeak(string $countryLeak): self
    {
        $this->countryLeak = $countryLeak;

        return $this;
    }

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getFacebookId(): string
    {
        return $this->facebookId;
    }

    public function setFacebookId(string $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getSex(): string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getCurrentDistrict(): ?string
    {
        return $this->currentDistrict;
    }

    public function setCurrentDistrict(?string $currentDistrict): self
    {
        $this->currentDistrict = $currentDistrict;

        return $this;
    }

    public function getCurrentCountry(): ?string
    {
        return $this->currentCountry;
    }

    public function setCurrentCountry(?string $currentCountry): self
    {
        $this->currentCountry = $currentCountry;

        return $this;
    }

    public function getCurrentState(): ?string
    {
        return $this->currentState;
    }

    public function setCurrentState(?string $currentState): self
    {
        $this->currentState = $currentState;

        return $this;
    }

    public function getHometownDistrict(): ?string
    {
        return $this->hometownDistrict;
    }

    public function setHometownDistrict(?string $hometownDistrict): self
    {
        $this->hometownDistrict = $hometownDistrict;

        return $this;
    }

    public function getHometownCountry(): ?string
    {
        return $this->hometownCountry;
    }

    public function setHometownCountry(?string $hometownCountry): self
    {
        $this->hometownCountry = $hometownCountry;

        return $this;
    }

    public function getHometownState(): ?string
    {
        return $this->hometownState;
    }

    public function setHometownState(?string $hometownState): self
    {
        $this->hometownState = $hometownState;

        return $this;
    }

    public function getRelationshipStatus(): ?string
    {
        return $this->relationshipStatus;
    }

    public function setRelationshipStatus(?string $relationshipStatus): self
    {
        $this->relationshipStatus = $relationshipStatus;

        return $this;
    }

    public function getWorkCompany(): ?string
    {
        return $this->workCompany;
    }

    public function setWorkCompany(?string $workCompany): self
    {
        $this->workCompany = $workCompany;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate10()
    {
        return $this->date10;
    }

    /**
     * @param mixed $date10
     */
    public function setDate10(?string $date10): void
    {
        $this->date10 = $date10;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeImmutable $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getFacebookCurrentAddress(): ?string
    {
        return $this->facebookCurrentAddress;
    }

    public function setFacebookCurrentAddress(?string $facebookCurrentAddress): self
    {
        $this->facebookCurrentAddress = $facebookCurrentAddress;

        return $this;
    }

    public function getFacebookHometownAddress(): ?string
    {
        return $this->facebookHometownAddress;
    }

    public function setFacebookHometownAddress(?string $facebookHometownAddress): self
    {
        $this->facebookHometownAddress = $facebookHometownAddress;

        return $this;
    }

    public function getFacebookBirthDate(): ?string
    {
        return $this->facebookBirthDate;
    }

    public function setFacebookBirthDate(?string $facebookBirthDate): self
    {
        $this->facebookBirthDate = $facebookBirthDate;

        return $this;
    }
}
