<?php

namespace App\Entity;

use App\Enum\ImportStatus;
use App\Repository\ImportHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImportHistoryRepository::class)]
class ImportHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startTime = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $entTime = null;

    #[ORM\Column(enumType: ImportStatus::class)]
    private ?ImportStatus $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logFilePath = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalLines = null;

    #[ORM\Column(nullable: true)]
    private ?int $successLines = null;

    #[ORM\Column(nullable: true)]
    private ?int $failedLines = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeImmutable $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEntTime(): ?\DateTimeImmutable
    {
        return $this->entTime;
    }

    public function setEntTime(?\DateTimeImmutable $entTime): static
    {
        $this->entTime = $entTime;

        return $this;
    }

    public function getStatus(): ?ImportStatus
    {
        return $this->status;
    }

    public function setStatus(ImportStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getLogFilePath(): ?string
    {
        return $this->logFilePath;
    }

    public function setLogFilePath(?string $logFilePath): static
    {
        $this->logFilePath = $logFilePath;

        return $this;
    }

    public function getTotalLines(): ?int
    {
        return $this->totalLines;
    }

    public function setTotalLines(?int $totalLines): static
    {
        $this->totalLines = $totalLines;

        return $this;
    }

    public function getSuccessLines(): ?int
    {
        return $this->successLines;
    }

    public function setSuccessLines(?int $successLines): static
    {
        $this->successLines = $successLines;

        return $this;
    }

    public function getFailedLines(): ?int
    {
        return $this->failedLines;
    }

    public function setFailedLines(?int $failedLines): static
    {
        $this->failedLines = $failedLines;

        return $this;
    }
}
