<?php

namespace App\Entity;

use App\Repository\PdfFileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PdfFileRepository::class)]
class PdfFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $FileName = null;

    #[ORM\Column(type: Types::BLOB)]
    private $FileData = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->FileName;
    }

    public function setFileName(string $FileName): static
    {
        $this->FileName = $FileName;

        return $this;
    }

    public function getFileData()
    {
        return $this->FileData;
    }

    public function setFileData($FileData): static
    {
        $this->FileData = $FileData;

        return $this;
    }
}
