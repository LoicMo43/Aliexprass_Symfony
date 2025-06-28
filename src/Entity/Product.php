<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    private $name;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'text')]
    private $moreInformations;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isBestSeller = false;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isNewArrival = false;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isFeatured = false;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isSpecialOffer = false;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\ManyToMany(targetEntity: Categories::class, inversedBy: 'products')]
    private $category;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ReviewsProduct::class)]
    private $reviewsProducts;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\Column(type: 'datetime', name: 'createdAt')]
    private $createdAt;

    #[ORM\Column(type: 'text', nullable: true)]
    private $tags;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\ManyToMany(targetEntity: Wishlist::class, mappedBy: 'products')]
    private Collection $wishlists;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->reviewsProducts = new ArrayCollection();
        $this->createdAt = new DateTime();
        $this->wishlists = new ArrayCollection();
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

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
     * @param string $moreInformations
     * @return $this
     */
    public function setMoreInformations(string $moreInformations): self
    {
        $this->moreInformations = $moreInformations;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsBestSeller(): ?bool
    {
        return $this->isBestSeller;
    }

    /**
     * @param bool|null $isBestSeller
     * @return $this
     */
    public function setIsBestSeller(?bool $isBestSeller): self
    {
        $this->isBestSeller = $isBestSeller;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsNewArrival(): ?bool
    {
        return $this->isNewArrival;
    }

    /**
     * @param bool|null $isNewArrival
     * @return $this
     */
    public function setIsNewArrival(?bool $isNewArrival): self
    {
        $this->isNewArrival = $isNewArrival;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsFeatured(): ?bool
    {
        return $this->isFeatured;
    }

    /**
     * @param bool|null $isFeatured
     * @return $this
     */
    public function setIsFeatured(?bool $isFeatured): self
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsSpecialOffer(): ?bool
    {
        return $this->isSpecialOffer;
    }

    /**
     * @param bool|null $isSpecialOffer
     * @return $this
     */
    public function setIsSpecialOffer(?bool $isSpecialOffer): self
    {
        $this->isSpecialOffer = $isSpecialOffer;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return $this
     */
    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategory(): array|Collection
    {
        return $this->category;
    }

    /**
     * @param Categories $category
     * @return $this
     */
    public function addCategory(Categories $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    /**
     * @param Categories $category
     * @return $this
     */
    public function removeCategory(Categories $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getReviewsProducts(): Collection
    {
        return $this->reviewsProducts;
    }

    /**
     * @param ReviewsProduct $reviewsProduct
     * @return $this
     */
    public function addReviewsProduct(ReviewsProduct $reviewsProduct): self
    {
        if (!$this->reviewsProducts->contains($reviewsProduct)) {
            $this->reviewsProducts[] = $reviewsProduct;
            $reviewsProduct->setProduct($this);
        }

        return $this;
    }

    /**
     * @param ReviewsProduct $reviewsProduct
     * @return $this
     */
    public function removeReviewsProduct(ReviewsProduct $reviewsProduct): self
    {
        // set the owning side to null (unless already changed)
        if ($this->reviewsProducts->removeElement($reviewsProduct) && $reviewsProduct->getProduct() === $this) {
            $reviewsProduct->setProduct(null);
        }

        return $this;
    }

    /**
     * @return string
     */
    #[Pure] public function __toString(): string
    {
        return $this->getName();
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
    public function getTags(): ?string
    {
        return $this->tags;
    }

    /**
     * @param string|null $tags
     * @return $this
     */
    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Wishlist>
     */
    public function getWishlists(): Collection
    {
        return $this->wishlists;
    }

    public function addWishlist(Wishlist $wishlist): self
    {
        if (!$this->wishlists->contains($wishlist)) {
            $this->wishlists->add($wishlist);
            $wishlist->addProduct($this);
        }

        return $this;
    }

    public function removeWishlist(Wishlist $wishlist): self
    {
        if ($this->wishlists->removeElement($wishlist)) {
            $wishlist->removeProduct($this);
        }

        return $this;
    }
}
