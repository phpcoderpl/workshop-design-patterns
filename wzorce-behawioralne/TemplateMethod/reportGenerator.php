<?php

interface ProReportGeneratorInterface
{
  public function generateReport();
}

abstract class Report implements ProReportGeneratorInterface
{
  protected abstract function generateHeader();
  protected abstract function generateData();
  protected abstract function generateFooter();

  public function generateReport()
  {
    $this->generateHeader();
    $this->generateData();
    $this->generateFooter();
  }
}

class PdfReport extends Report
{
  protected function generateHeader()
  {
    // Generowanie nagłówka w formacie PDF
    echo "Generowanie nagłówka w formacie PDF\n";
  }

  protected function generateData()
  {
    // Generowanie danych w formacie PDF
    echo "Generowanie danych w formacie PDF\n";
  }

  protected function generateFooter()
  {
    // Generowanie stopki w formacie PDF
    echo "Generowanie stopki w formacie PDF\n";
  }
}

class CsvReport extends Report
{
  protected function generateHeader()
  {
    // Generowanie nagłówka w formacie CSV
    echo "Generowanie nagłówka w formacie CSV\n";
  }

  protected function generateData()
  {
    // Generowanie danych w formacie CSV
    echo "Generowanie danych w formacie CSV\n";
  }

  protected function generateFooter()
  {
    // Generowanie stopki w formacie CSV
    echo "Generowanie stopki w formacie CSV\n";
  }
}

class HtmlReport extends Report
{
  protected function generateHeader()
  {
    // Generowanie nagłówka w formacie HTML
    echo "Generowanie nagłówka w formacie HTML\n";
  }

  protected function generateData()
  {
    // Generowanie danych w formacie HTML
    echo "Generowanie danych w formacie HTML\n";
  }

  protected function generateFooter()
  {
    // Generowanie stopki w formacie HTML
    echo "Generowanie stopki w formacie HTML\n";
  }
}

function clientCode(ProReportGeneratorInterface $reportGenerator)
{
  echo $reportGenerator->generateReport();
}

// Przykład użycia

clientCode(new PdfReport());
clientCode(new CsvReport());
clientCode(new HtmlReport());
