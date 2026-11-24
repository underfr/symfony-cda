<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


class UploadFileService
{

    public function __construct(
        private readonly string $targetDirectory,
        private readonly SluggerInterface $slugger,
    ) {}

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $this->slugger->slug($originalFilename);
        $fileName = $safeName . '-' . uniqid() . '.' . $file->guessExtension();
        $file->move($this->getTargetDirectory(), $fileName);
        return $fileName;
    }

}
