<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $reference;

    #[ORM\Column(type: 'string', length: 255)]
    private $fullname;

    #[ORM\Column(type: 'string', length: 255)]
    private $carrierName;

    #[ORM\Column(type: 'float')]
    private $carrierPrice;

    #[ORM\Column(type: 'text')]
    private $deliveryAddress;

    #[ORM\Column(type: 'boolean')]
    private $isPaid = false;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'text', nullable: true)]
    private $moreInformations;

    #[ORM\OneToMany(mappedBy: 'orders', targetEntity: OrderDetails::class)]
    private $orderDetails;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\Column(type: 'float')]
    private $subTotalHT;

    #[ORM\Column(type: 'float')]
    private $taxe;

    #[ORM\Column(type: 'float')]
    private $subTotalTTC;

    #[Pure] public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     * @return $this
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    /**
     * @param string $fullname
     * @return $this
     */
    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCarrierName(): ?string
    {
        return $this->carrierName;
    }

    /**
     * @param string $carrierName
     * @return $this
     */
    public function setCarrierName(string $carrierName): self
    {
        $this->carrierName = $carrierName;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getCarrierPrice(): ?float
    {
        return $this->carrierPrice;
    }

    /**
     * @param float $carrierPrice
     * @return $this
     */
    public function setCarrierPrice(float $carrierPrice): self
    {
        $this->carrierPrice = $carrierPrice;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    /**
     * @param string $deliveryAddress
     * @return $this
     */
    public function setDeliveryAddress(string $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    /**
     * @param bool $isPaid
     * @return $this
     */
    public function setIsPaid(bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMoreInformations(): ?string
    {
        return $this->moreInformations;
    }

    /**
     * @param string|null $moreInformations
     * @return $this
     */
    public function setMoreInformations(?string $moreInformations): self
    {
        $this->moreInformations = $moreInformations;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    /**
     * @param OrderDetails $orderDetail
     * @return $this
     */
    public function addOrderDetail(OrderDetails $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails[] = $orderDetail;
            $orderDetail->setOrders($this);
        }

        return $this;
    }

    /**
     * @param OrderDetails $orderDetail
     * @return $this
     */
    public function removeOrderDetail(OrderDetails $orderDetail): self
    {
        // set the owning side to null (unless already changed)
        if ($this->orderDetails->removeElement($orderDetail) && $orderDetail->getOrders() === $this) {
            $orderDetail->setOrders(null);
        }

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getSubTotalHT(): ?float
    {
        return $this->subTotalHT;
    }

    /**
     * @param float $subTotalHT
     * @return $this
     */
    public function setSubTotalHT(float $subTotalHT): self
    {
        $this->subTotalHT = $subTotalHT;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    /**
     * @param float $taxe
     * @return $this
     */
    public function setTaxe(float $taxe): self
    {
        $this->taxe = $taxe;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getSubTotalTTC(): ?float
    {
        return $this->subTotalTTC;
    }

    /**
     * @param float $subTotalTTC
     * @return $this
     */
    public function setSubTotalTTC(float $subTotalTTC): self
    {
        $this->subTotalTTC = $subTotalTTC;

        return $this;
    }
}
