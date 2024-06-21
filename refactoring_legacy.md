# Refactoring Legacy

W starszych projektach, które nie były projektowane z myślą o nowoczesnych praktykach programistycznych należy przeprowadzić kontrolowaną refaktoryzację. Najlepiej z pomocą osoby doświadczonej w tego typu działaniach.

## Podstawowe kroki

1. **Zrozumienie obecnej struktury**: Przeanalizuj dokładnie istniejący kod, zidentyfikuj zależności i zrozum, jak poszczególne części systemu komunikują się ze sobą.

2. **Testy jednostkowe**: Przed przystąpieniem do jakichkolwiek zmian, upewnij się, że masz solidny zestaw testów jednostkowych. To pozwoli ci na wykrycie błędów, które mogą powstać w wyniku refaktoryzacji.

3. **Modularizacja krok po kroku**: Zacznij od najmniejszych, najłatwiejszych do oddzielenia części. Wyodrębnij je do osobnych klas lub modułów i upewnij się, że działają poprawnie, zanim przejdziesz do kolejnych.

4. **Warstwowanie architektury**:
   - **Warstwa prezentacji**: Zajmij się kontrolerami i przenieś logikę biznesową do osobnych serwisów.
   - **Warstwa serwisów (biznesowa)**: Przenieś logikę biznesową z kontrolerów do serwisów. Serwisy powinny być odpowiedzialne tylko za jedną rzecz i mieć minimalne zależności.
   - **Warstwa dostępu do danych**: Zajmij się logiką dostępu do danych. Przenieś kod związany z bazą danych do osobnych repozytoriów.

5. **Refaktoryzacja kontrolerów**: Oczyść kontrolery, przenosząc logikę biznesową i dostępu do danych do odpowiednich warstw.

6. **Podział na mniejsze moduły**: Stopniowo podziel aplikację na mniejsze moduły lub mikroserwisy, jeśli to możliwe.

7. **Automatyzacja testów i ciągła integracja**: Upewnij się, że procesy ciągłej integracji (CI) i ciągłego dostarczania (CD) są wprowadzone, aby szybko wykrywać i naprawiać problemy.

8. **Edukacja zespołu**: Upewnij się, że cały zespół jest świadomy nowych praktyk i architektury. Szkolenia i dokumentacja mogą być tutaj bardzo pomocne.

## Od czego zacząć

W przypadku projektu w PHP, zalecam zacząć od refaktoryzacji serwisów, ponieważ to one często zawierają logikę biznesową, która może być najbardziej skomplikowana i trudna do utrzymania w dużych kontrolerach. Po uporządkowaniu serwisów, reszta refaktoryzacji powinna być łatwiejsza. Oto bardziej szczegółowy plan działania:

### Krok 1: Przeniesienie logiki biznesowej do serwisów

1. **Zidentyfikuj logikę biznesową w kontrolerach**: Przejrzyj metody w kontrolerach i wyodrębnij logikę biznesową.
2. **Utwórz warstwę serwisów**: Utwórz odpowiednie klasy serwisów i przenieś do nich logikę biznesową.
3. **Zaktualizuj kontrolery**: Zaktualizuj kontrolery, aby korzystały z nowych serwisów zamiast zawierać logikę biznesową bezpośrednio.

### Krok 2: Przeniesienie dostępu do danych do repozytoriów

1. **Zidentyfikuj logikę dostępu do danych**: Przejrzyj serwisy i kontrolery, aby zidentyfikować kod, który bezpośrednio komunikuje się z bazą danych.
2. **Utwórz warstwę repozytoriów**: Utwórz odpowiednie klasy repozytoriów i przenieś do nich logikę dostępu do danych.
3. **Zaktualizuj serwisy**: Zaktualizuj serwisy, aby korzystały z repozytoriów zamiast bezpośrednio komunikować się z bazą danych.

### Przykład kodu serwisu w PHP

```php
namespace App\Services;

use App\Repositories\ExampleRepository;
use App\Mappers\ExampleMapper;

class ExampleService {
    protected $repository;
    protected $mapper;

    public function __construct(ExampleRepository $repository, ExampleMapper $mapper) {
        $this->repository = $repository;
        $this->mapper = $mapper;
    }

    public function getAllExamples() {
        $models = $this->repository->findAll();
        return array_map([$this->mapper, 'toDTO'], $models);
    }

    public function createExample($dto) {
        $model = $this->mapper->toModel($dto);
        $this->repository->save($model);
    }
}
```

### Krok 3: Przeniesienie modeli do odpowiednich plików

1. **Utwórz folder modeli**: Utwórz folder dla modeli, jeśli jeszcze nie istnieje.
2. **Przenieś modele**: Przenieś wszystkie klasy modeli do nowo utworzonego folderu.
3. **Zaktualizuj namespace**: Upewnij się, że namespace dla przeniesionych klas modeli jest odpowiednio zaktualizowany.

### Przykład kodu modelu w PHP

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExampleModel extends Model {
    protected $table = 'examples';

    protected $fillable = [
        'name',
        'description'
    ];
}
```

### Krok 4: Utworzenie DTO i Mapperów

1. **Utwórz folder dla DTO i Mapperów**: Utwórz odpowiednie foldery dla DTO i Mapperów.
2. **Stwórz klasy DTO**: Stwórz klasy DTO dla transferu danych między warstwami.
3. **Stwórz klasy Mapperów**: Stwórz klasy Mapperów do konwersji modeli na DTO i odwrotnie.

### Przykład kodu DTO w PHP

```php
namespace App\DTOs;

class ExampleDTO {
    public $id;
    public $name;
    public $description;

    public function __construct($id, $name, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }
}
```

### Przykład kodu Mapper w PHP

```php
namespace App\Mappers;

use App\Models\ExampleModel;
use App\DTOs\ExampleDTO;

class ExampleMapper {
    public function toDTO(ExampleModel $model) {
        return new ExampleDTO($model->id, $model->name, $model->description);
    }

    public function toModel(ExampleDTO $dto) {
        $model = new ExampleModel();
        $model->id = $dto->id;
        $model->name = $dto->name;
        $model->description = $dto->description;
        return $model;
    }
}
```

### Krok 5: Refaktoryzacja i tworzenie testów

1. **Refaktoryzacja**: W miarę przenoszenia kodu do nowych warstw, refaktoryzuj kod, aby był bardziej czytelny i zrozumiały.
2. **Tworzenie testów**: Twórz testy jednostkowe i integracyjne dla nowych serwisów, repozytoriów, DTO i mapperów.
3. **Automatyzacja**: Zaimplementuj automatyzację testów i integracji ciągłej, aby mieć pewność, że refaktoryzacja nie wprowadza nowych błędów.

### Podsumowanie

Rozpoczęcie od przeniesienia logiki biznesowej do serwisów pozwoli na stopniowe porządkowanie kodu bez dużego ryzyka wprowadzenia błędów. Po tym etapie przeniesienie logiki dostępu do danych do repozytoriów oraz uporządkowanie modeli, DTO i mapperów będzie znacznie łatwiejsze. Upewnij się, że każda zmiana jest wspierana przez odpowiednie testy, co umożliwi bezpieczne refaktoryzowanie dużego projektu legacy.

## Organizacja plików i folderów

Oczywiście, oto bardziej szczegółowy układ folderów w pojedynczym module, który odpowiada architekturze warstwowej w projekcie PHP:

```php
/app
├── Modules
│   └── NazwaModulu
│       ├── Controllers
│       │   ├── ExampleController.php
│       ├── Services
│       │   ├── ExampleService.php
│       ├── Repositories
│       │   ├── ExampleRepository.php
│       ├── Models
│       │   ├── ExampleModel.php
│       ├── DTOs
│       │   ├── ExampleDTO.php
│       ├── Mappers
│       │   ├── ExampleMapper.php
│       ├── Exceptions
│       │   ├── ExampleException.php
│       ├── Utils
│       │   ├── ExampleUtil.php
│       └── Config
│           ├── config.php
```

### Opis folderów

1. **Controllers**: Zawiera kontrolery, które obsługują żądania HTTP i delegują zadania do warstwy serwisów.
2. **Services**: Zawiera serwisy, które implementują logikę biznesową aplikacji.
3. **Repositories**: Zawiera repozytoria odpowiedzialne za interakcję z bazą danych.
4. **Models**: Zawiera modele danych, które reprezentują encje w bazie danych.
5. **DTOs**: Zawiera obiekty transferowe (DTO), które są używane do przesyłania danych między warstwami aplikacji.
6. **Mappers**: Zawiera klasy mapperów, które konwertują modele na DTO i odwrotnie.
7. **Exceptions**: Zawiera klasy wyjątków używane w aplikacji.
8. **Utils**: Zawiera klasy pomocnicze i narzędziowe, które są używane w różnych częściach aplikacji.
9. **Config**: Zawiera pliki konfiguracyjne specyficzne dla tego modułu.

### Przykład kodu dla poszczególnych warstw

#### Controller (ExampleController.php)

```php
namespace App\Modules\NazwaModulu\Controllers;

use App\Modules\NazwaModulu\Services\ExampleService;
use App\Modules\NazwaModulu\DTOs\ExampleDTO;

class ExampleController {
    protected $service;

    public function __construct(ExampleService $service) {
        $this->service = $service;
    }

    public function getAll() {
        return $this->service->getAllExamples();
    }

    public function create($data) {
        $dto = new ExampleDTO($data);
        return $this->service->createExample($dto);
    }
}
```

#### Service (ExampleService.php)

```php
namespace App\Modules\NazwaModulu\Services;

use App\Modules\NazwaModulu\Repositories\ExampleRepository;
use App\Modules\NazwaModulu\Mappers\ExampleMapper;
use App\Modules\NazwaModulu\DTOs\ExampleDTO;

class ExampleService {
    protected $repository;
    protected $mapper;

    public function __construct(ExampleRepository $repository, ExampleMapper $mapper) {
        $this->repository = $repository;
        $this->mapper = $mapper;
    }

    public function getAllExamples() {
        $models = $this->repository->findAll();
        return array_map([$this->mapper, 'toDTO'], $models);
    }

    public function createExample(ExampleDTO $dto) {
        $model = $this->mapper->toModel($dto);
        return $this->repository->save($model);
    }
}
```

#### Repository (ExampleRepository.php)

```php
namespace App\Modules\NazwaModulu\Repositories;

use App\Modules\NazwaModulu\Models\ExampleModel;

class ExampleRepository {
    public function findAll() {
        // Implementacja pobierania wszystkich modeli z bazy danych
        return ExampleModel::all();
    }

    public function save(ExampleModel $model) {
        // Implementacja zapisywania modelu do bazy danych
        return $model->save();
    }
}
```

#### Model (ExampleModel.php)

```php
namespace App\Modules\NazwaModulu\Models;

use Illuminate\Database\Eloquent\Model;

class ExampleModel extends Model {
    protected $table = 'examples';

    protected $fillable = [
        'name',
        'description'
    ];
}
```

#### DTO (ExampleDTO.php)

```php
namespace App\Modules\NazwaModulu\DTOs;

class ExampleDTO {
    public $id;
    public $name;
    public $description;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'];
        $this->description = $data['description'];
    }
}
```

#### Mapper (ExampleMapper.php)

```php
namespace App\Modules\NazwaModulu\Mappers;

use App\Modules\NazwaModulu\Models\ExampleModel;
use App\Modules\NazwaModulu\DTOs\ExampleDTO;

class ExampleMapper {
    public function toDTO(ExampleModel $model) {
        return new ExampleDTO([
            'id' => $model->id,
            'name' => $model->name,
            'description' => $model->description,
        ]);
    }

    public function toModel(ExampleDTO $dto) {
        return new ExampleModel([
            'id' => $dto->id,
            'name' => $dto->name,
            'description' => $dto->description,
        ]);
    }
}
```

## To jaki jest plan?

Rozumiem, że brak testów jednostkowych i bardzo duże metody w kontrolerach stanowią ogromne wyzwanie. W takiej sytuacji można podejść do problemu w następujący sposób:

### Krok 1: Audyt kodu i identyfikacja najważniejszych punktów

- **Zidentyfikuj krytyczne części systemu**: Określ, które funkcje są najważniejsze dla działania aplikacji. Może to być na podstawie użycia, częstotliwości błędów lub ważności biznesowej.
- **Dokumentacja**: Sporządź dokładną dokumentację obecnej struktury i przepływu danych.

### Krok 2: Tworzenie testów end-to-end (E2E)

- **Testy E2E**: Na początek utwórz testy end-to-end, które sprawdzają najważniejsze funkcjonalności aplikacji z perspektywy użytkownika. To pozwoli ci na zachowanie pewności, że podstawowe funkcje działają podczas refaktoryzacji.
- **Narzędzia**: Skorzystaj z narzędzi takich jak Selenium, Cypress, czy Robot Framework, które pomogą w automatyzacji testów.

### Krok 3: Wprowadzenie podstawowych testów integracyjnych

- **Testy integracyjne**: Utwórz testy, które sprawdzają interakcje między różnymi komponentami systemu, np. między kontrolerem a warstwą serwisów.

### Krok 4: Stopniowe wydzielanie kodu

- **Refaktoryzacja metod**: Podziel duże metody w kontrolerach na mniejsze, bardziej zrozumiałe części. Staraj się przenosić logikę do nowych klas serwisowych.
  - Wyodrębniaj logikę biznesową do klas serwisowych.
  - Przenoś logikę dostępu do danych do warstwy repozytoriów.
- **Utwórz serwisy**: Każdy wyodrębniony kawałek logiki umieszczaj w odpowiednich serwisach. Staraj się, aby każdy serwis odpowiadał za jedno konkretne zadanie.
- **Refaktoryzacja krok po kroku**: Każdą zmianę wprowadzaj w małych krokach, zawsze sprawdzając, czy aplikacja działa poprawnie przy pomocy testów E2E i integracyjnych.

### Krok 5: Tworzenie testów jednostkowych

- **Testy jednostkowe**: Po podzieleniu kodu na mniejsze części zacznij tworzyć testy jednostkowe dla nowych serwisów i metod. To będzie łatwiejsze, gdy metody będą krótsze i bardziej zrozumiałe.
- **Priorytetyzacja**: Skoncentruj się na testowaniu najważniejszych i najbardziej ryzykownych części kodu.

### Krok 6: Automatyzacja i ciągła integracja

- **CI/CD**: Wprowadź narzędzia do ciągłej integracji i ciągłego dostarczania (np. Jenkins, GitLab CI, Travis CI), aby automatycznie uruchamiać testy przy każdej zmianie kodu.
- **Monitorowanie**: Ustal procesy monitorowania i alarmowania, aby szybko wykrywać problemy w produkcji.

### Przykładowy plan działania:

1. **Audyt kodu i stworzenie testów E2E** (1-2 tygodnie).
2. **Podział dużych metod w kontrolerach na mniejsze kawałki i utworzenie serwisów** (2-3 miesiące, zależnie od wielkości projektu).
3. **Tworzenie podstawowych testów integracyjnych dla nowych serwisów** (równolegle z refaktoryzacją).
4. **Stopniowe wprowadzanie testów jednostkowych** (ciągły proces, kontynuowany w miarę refaktoryzacji).
5. **Implementacja CI/CD** (1-2 tygodnie).
6. **Edukacja zespołu i dokumentacja** (ciągły proces).

### Wskazówki:

- **Komunikacja zespołu**: Upewnij się, że zespół jest zaangażowany i informowany o postępach. Regularne spotkania mogą pomóc w koordynacji prac.
- **Priorytetyzacja**: Skoncentruj się na najbardziej krytycznych częściach kodu najpierw.
- **Cierpliwość**: Refaktoryzacja starego systemu może zająć dużo czasu. Ustal realistyczne cele i kroki milowe.

