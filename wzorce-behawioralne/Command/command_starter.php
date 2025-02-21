<?php

namespace NoCommand;

class SimpleOrder
{
  private $number;
  private $progress = 0;

  public function __construct(string $number)
  {
    $this->number = $number;
  }

  public function open()
  {
    echo "order {$this->number} is now open \n";
  }

  public function process()
  {
    $this->progress += 10;
    echo "processing order : {$this->progress} %\n";
  }

  public function close()
  {
    echo "order is now closed \n";
  }
}

$order = new SimpleOrder('ABC 11');

$order->open();

for ($i = 0; $i < 4; $i++) {
  $order->process();
}

$order->close();

$order->open();

for ($i = 0; $i < 3; $i++) {
  $order->process();
}

$order->close();

/**
 * FIXME: Brak możliwości cofnięcia operacji:
 * Wzorzec Command jest bardzo przydatny, gdy chcesz mieć możliwość cofania operacji. W tym przypadku, operacje na Order są bezpowrotne.
 *
 * FIXME: Brak elastyczności:
 * Wzorzec Command pozwala na elastyczne i łatwe dodawanie nowych operacji bez konieczności modyfikowania istniejącego kodu. W tym przypadku, dodanie nowej operacji wymaga modyfikacji kodu.
 *
 * FIXME: Trudność w utrzymaniu:
 * Wzorzec Command upraszcza kod, wyodrębniając operacje do oddzielnych klas. Bez wzorca, wszystko jest skumulowane w jednej klasie, co może utrudniać zrozumienie i utrzymanie kodu.
 *
 * FIXME: Bezpośrednie wywołanie metod na obiekcie Order:
 * To jest sprzeczne z zasadą enkapsulacji w programowaniu obiektowym, która zaleca, aby dane obiektu były prywatne, a interakcja z obiektem odbywała się za pośrednictwem jego metod. Może to prowadzić do niezamierzonego zachowania i błędów.
 */
