<?php

class DatabaseConnection
{
  private static $instance;
  private $connection;

  private function __construct()
  {
    // Utworzenie połączenia z bazą danych
    $this->connection = new PDO('mysql:host=localhost;dbname=mydatabase', 'username', 'password');
  }

  public static function getInstance()
  {
    if (self::$instance == null) {
      self::$instance = new DatabaseConnection();
    }

    return self::$instance;
  }

  public function getConnection()
  {
    return $this->connection;
  }
}

$dbConnection = DatabaseConnection::getInstance()->getConnection();
