<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(mercure: true)]
#[ORM\Entity]
class EventOutline
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    /** @phpstan-ignore property.onlyRead */
    private ?Uuid $id;

    #[ORM\Column]
    #[Assert\NotBlank]
    public string $name = '';

    /** @var Collection<int, Chair> $chairs&iterable<Chair> */
    #[ORM\OneToMany(mappedBy: 'eventOutline', targetEntity: Chair::class, cascade: ['persist', 'remove'])]
    #[Assert\NotBlank]
    private Collection $chairs;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->chairs = new ArrayCollection();
    }

    public function addChair(Chair $chair): self
    {
        if (!$this->chairs->contains($chair)) {
            $this->chairs->add($chair);
        }

        return $this;
    }

    public function removeChair(Chair $chair): self
    {
        if ($this->chairs->contains($chair)) {
            $this->chairs->removeElement($chair);
        }

        return $this;
    }

    /** @return  Collection<int, Chair> */
    public function getChairs(): Collection
    {
        return $this->chairs;
    }
}
