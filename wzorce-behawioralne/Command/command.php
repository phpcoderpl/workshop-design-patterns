<?php

namespace Command;

interface CommandInterface
{
  public function execute();
  public function undo();
}

abstract class Command implements CommandInterface
{

  protected $backupOrder;
  protected $order;

  public function __construct(Order $order)
  {
    $this->order = $order;
  }

  public function backup()
  {
    $this->backupOrder = $this->order;
  }

  public function execute()
  {
  }

  public function undo()
  {
    $this->order = $this->backupOrder;
  }
}

class OpenOrderCommand extends Command
{
  public function execute()
  {
    $this->backup();
    $this->order->open();
  }
}

class ProcessOrderCommand extends Command
{
  public function execute()
  {
    $this->backup();
    $this->order->process();
  }
}

class CloseOrderCommand extends Command
{
  public function execute()
  {
    $this->backup();
    $this->order->close();
  }
}

// -----------

class Order
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


$order = new Order('ABC 11');
$open = new OpenOrderCommand($order);
$process = new ProcessOrderCommand($order);
$close = new CloseOrderCommand($order);

foreach ([
  $open,
  $process,
  $process,
  $process,
  $process,
  $close,
  $open,
  $process,
  $process,
  $process,
  $process,
  $process,
  $process,
  $close
] as $command) {
  $command->execute();
}
