<?php

namespace App\SocialShares\Entity;

use App\Entity\Client;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'share_transactions')]
class ShareTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $type = ''; // 'PURCHASE' or 'DIVIDEND'

    #[ORM\Column(type: 'integer')]
    private int $shares = 0;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2)]
    private string $amount = '0.00';

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $date = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    // Getters and Setters
    public function getId(): ?int { return $this->id; }

    public function getClient(): ?Client { return $this->client; }
    public function setClient(Client $client): self { $this->client = $client; return $this; }

    public function getType(): string { return $this->type; }
    public function setType(string $type): self { $this->type = $type; return $this; }

    public function getShares(): int { return $this->shares; }
    public function setShares(int $shares): self { $this->shares = $shares; return $this; }

    public function getAmount(): string { return $this->amount; }
    public function setAmount(string $amount): self { $this->amount = $amount; return $this; }

    public function getDate(): ?\DateTime { return $this->date; }
    public function setDate(\DateTime $date): self { $this->date = $date; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
}
