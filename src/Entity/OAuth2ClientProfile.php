<?php

namespace App\Entity;

use App\Repository\OAuth2ClientProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use League\Bundle\OAuth2ServerBundle\Model\Client;

#[ORM\Entity(repositoryClass: OAuth2ClientProfileRepository::class)]
class OAuth2ClientProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Client::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(
        name: 'client_identifier',
        referencedColumnName: 'identifier',
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private ?Client $client = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
