<?php

namespace NoPrototype;

class Order
{
  private $products;
  private $customer;
  private $deliveryAddress;

  public function __construct($products, $customer, $deliveryAddress)
  {
    $this->products = $products;
    $this->customer = $customer;
    $this->deliveryAddress = $deliveryAddress;
  }

  public function copy(): Order
  {
    $copiedProducts = $this->copyProducts();
    return new Order($copiedProducts, $this->customer, $this->deliveryAddress);
  }

  private function copyProducts()
  {
    $copiedProducts = [];
    foreach ($this->products as $product) {
      $copiedProducts[] = $product;
    }
    return $copiedProducts;
  }

  // Getters and setters for order details
  // ...
}

/**
 * FIXME: Dont Repeat Yourself:
 * W przypadku skomplikowanych obiektów, które mają wiele właściwości,
 * konieczne będzie ręczne kopiowanie każdej właściwości.
 * To prowadzi do powielania kodu i zwiększa ryzyko popełnienia błędów.
 *
 * FIXME: Zależności:
 * Jeśli struktura oryginalnego obiektu ulegnie zmianie,
 * konieczne będzie również dostosowanie kodu obejmującego funkcjonalność kopiowania,
 * co może być czasochłonne i podatne na błędy.
 *
 * FIXME: Brak elastyczności:
 * Trudno jest dynamicznie rozszerzać i modyfikować proces kopiowania.
 * W przypadku zmiany wymagań dotyczących kopiowania,
 * konieczne będzie wprowadzenie zmian w wielu miejscach w kodzie.
 */
