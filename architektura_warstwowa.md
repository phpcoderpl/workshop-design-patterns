
# Architektura Warstwowa
- **Warstwa prezentacji** (Presentation Layer): odpowiada za interakcję z użytkownikiem, prezentowanie danych.
- **Warstwa logiki biznesowej** (Business Logic Layer): przetwarza dane wejściowe od warstwy prezentacji i wykonuje właściwą logikę biznesową.
- **Warstwa dostępu do danych** (Data Access Layer): zajmuje się przechowywaniem i pobieraniem danych z baz danych lub innych źródeł danych.
- **Warstwa danych** (Data Layer): definiuje struktury danych, które są używane w aplikacji.

```php


```php
// Przykład prostego podziału na warstwy w PHP:

// Warstwa danych
class Product {
    public $id;
    public $name;
    public $price;
}

// Warstwa dostępu do danych
class ProductRepository {
    public function find($id) {
        // Znajdź produkt w bazie danych
        return new Product();
    }
}

// Warstwa logiki biznesowej
class ProductService {
    protected $repository;

    public function __construct(ProductRepository $repository) {
        $this->repository = $repository;
    }

    public function getProduct($id) {
        return $this->repository->find($id);
    }
}

// Warstwa prezentacji
class ProductController {
    protected $service;

    public function __construct(ProductService $service) {
        $this->service = $service;
    }

    public function show($id) {
        $product = $this->service->getProduct($id);
        // Renderuj widok z danymi produktu
        echo "Product: " . $product->name;
    }
}

```