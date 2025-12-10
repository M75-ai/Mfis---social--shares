<?php

namespace App\SocialShares\Entity;

use App\Entity\Client;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'social_shares')]
class SocialShare
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 0;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2)]
    private string $unitPrice = '0.00';

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2)]
    private string $totalAmount = '0.00';

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $purchaseDate = null;

    public function getId(): ?int { return $this->id; }

    public function getClient(): ?Client { return $this->client; }
    public function setClient(Client $client): self { $this->client = $client; return $this; }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $quantity): self { $this->quantity = $quantity; return $this; }

    public function getUnitPrice(): string { return $this->unitPrice; }
    public function setUnitPrice(string $unitPrice): self { $this->unitPrice = $unitPrice; return $this; }

    public function getTotalAmount(): string { return $this->totalAmount; }
    public function setTotalAmount(string $totalAmount): self { $this->totalAmount = $totalAmount; return $this; }

    public function getPurchaseDate(): ?\DateTime { return $this->purchaseDate; }
    public function setPurchaseDate(\DateTime $purchaseDate): self { $this->purchaseDate = $purchaseDate; return $this; }
}
