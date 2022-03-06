<?php

namespace App\Entity;

use App\Repository\CartDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartDetailsRepository::class)]
class CartDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $productName;

    #[ORM\Column(type: 'float')]
    private $productPrice;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\Column(type: 'float')]
    private $subTotalHT;

    #[ORM\Column(type: 'float')]
    private $taxe = 0;

    #[ORM\Column(type: 'float')]
    private $subTotalTTC;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'CartDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private $Carts;

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
    public function getProductName(): ?string
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     * @return $this
     */
    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProductPrice(): ?string
    {
        return $this->productPrice;
    }

    /**
     * @param string $productPrice
     * @return $this
     */
    public function setProductPrice(string $productPrice): self
    {
        $this->productPrice = $productPrice;

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
        return $this->taxe*100;
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

    /**
     * @return Cart|null
     */
    public function getCarts(): ?Cart
    {
        return $this->Carts;
    }

    /**
     * @param Cart|null $Carts
     * @return $this
     */
    public function setCarts(?Cart $Carts): self
    {
        $this->Carts = $Carts;

        return $this;
    }
}
