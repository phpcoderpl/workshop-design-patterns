<?php

namespace Composite;

interface Employee
{
  public function getSalary(): float;
}

class IndividualEmployee implements Employee
{
  private $salary;

  public function __construct(float $salary)
  {
    $this->salary = $salary;
  }

  public function getSalary(): float
  {
    return $this->salary;
  }
}

class Team implements Employee
{
  private $employees;

  public function __construct()
  {
    $this->employees = [];
  }

  public function addEmployee(Employee $employee): void
  {
    $this->employees[] = $employee;
  }

  public function getSalary(): float
  {
    $totalSalary = 0;
    foreach ($this->employees as $employee) {
      $totalSalary += $employee->getSalary();
    }
    return $totalSalary;
  }
}
