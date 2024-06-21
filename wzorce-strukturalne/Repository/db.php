<?php

namespace Repository;

interface UserRepositoryInterface
{
  public function find($id);
  public function save(User $user);
  public function remove(User $user);
}

class UserRepository implements UserRepositoryInterface
{
  protected $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  public function find($id)
  {
    $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $data = $stmt->fetch();
    if ($data) {
      $user = new User($data['id'], $data['name'], $data['email']);
      return $user;
    }
    return null;
  }

  public function save(User $user)
  {
    if ($user->getId()) {
      $stmt = $this->db->prepare('UPDATE users SET name = :name, email = :email WHERE id = :id');
      return $stmt->execute([
        'name' => $user->getName(),
        'email' => $user->getEmail(),
        'id' => $user->getId(),
      ]);
    } else {
      $stmt = $this->db->prepare('INSERT INTO users (name, email) VALUES (:name, :email)');
      $stmt->execute([
        'name' => $user->getName(),
        'email' => $user->getEmail(),
      ]);
      $user->setId($this->db->lastInsertId());
    }
  }

  public function remove(User $user)
  {
    if ($user->getId()) {
      $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
      return $stmt->execute([
        'id' => $user->getId(),
      ]);
    }
  }
}

class User
{
  protected $id;
  protected $name;
  protected $email;

  public function __construct($id = null, $name, $email)
  {
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
  }

  // Gettery i settery
}
