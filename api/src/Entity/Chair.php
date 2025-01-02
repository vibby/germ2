<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(mercure: true)]
#[ORM\Entity]
class Chair
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

    #[ORM\ManyToOne(targetEntity: EventOutline::class, inversedBy: 'chairs')]
    #[Assert\NotBlank]
    public EventOutline $eventOutline;

    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
