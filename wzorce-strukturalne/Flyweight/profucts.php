<?php

class ProductFlyweight
{
  private string $category;
  private float $price;

  public function __construct(string $category, float $price)
  {
    $this->category = $category;
    $this->price = $price;
  }

  public function renderProductInfo(string $name, string $description): string
  {
    return "Produkt: $name, Opis: $description, Kategoria: $this->category, Cena: $this->price";
  }
}

class FlyweightFactory
{
  private array $flyweights = [];

  public function getFlyweight(string $category, float $price): ProductFlyweight
  {
    $key = md5($category . $price);
    if (!isset($this->flyweights[$key])) {
      $this->flyweights[$key] = new ProductFlyweight($category, $price);
    }
    return $this->flyweights[$key];
  }
}

// Klient
$factory = new FlyweightFactory();
$flyweight = $factory->getFlyweight('elektronika', 299.99);
echo $flyweight->renderProductInfo('Smartfon XYZ', 'Nowoczesny i stylowy');
