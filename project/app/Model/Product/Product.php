<?php

namespace App\Model\Product;

use App\Model\Product\ProductRepository;
use App\Model\Stock\Stock;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Product\ProductRepository")
 * @ORM\Table(name="products")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ean;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $shortDesc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $manufacturer;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @ORM\OneToOne(targetEntity="Stock", mappedBy="product")
     */
    private $stock;

    public function __construct()
    {
        $this->stock = new Stock();
    }

    // Getters and setters

    public function getId()
    {
        return $this->id;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getEan()
    {
        return $this->ean;
    }

    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getShortDesc()
    {
        return $this->shortDesc;
    }

    public function setShortDesc($shortDesc)
    {
        $this->shortDesc = $shortDesc;
    }

    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function setStock(int $stock)
    {
        $this->stock = $stock;
    }

}
