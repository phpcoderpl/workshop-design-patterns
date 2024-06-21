<?php

class MyIterator implements \Iterator
{

  private $collection;
  private $position = 0;

  public function __construct($collection)
  {
    $this->collection = $collection;
  }

  public function rewind(): void
  {
    $this->position = 0;
  }

  public function key()
  {
    return $this->position;
  }

  public function next(): void
  {
    $this->position++;
  }

  public function current()
  {
    return $this->collection->getItems()[$this->position];
  }

  public function valid(): bool
  {
    return isset($this->collection->getItems()[$this->position]);
  }
}

class WordsCollection implements \IteratorAggregate
{

  private $items = [];

  public function getItems()
  {
    return $this->items;
  }

  public function addItem($item)
  {
    $this->items[] = $item;
  }

  public function getIterator(): Traversable
  {
    return new MyIterator($this);
  }
}


$collection = new WordsCollection();

$sentence = explode(' ', "Crazy lazy dog i walking by the sea");

foreach ($sentence as $word) {
  $collection->addItem($word);
}

echo "Hello \n\n";
foreach ($collection->getIterator() as $item) {
  echo "- {$item} \n";
}
