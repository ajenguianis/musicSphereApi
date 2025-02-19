<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Repository\MusicGenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
final class ImportBrandsController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MusicGenreRepository $musicGenreRepository
    ) {}

    #[Route('/import-brands', name: 'import_brands', methods: ['POST'])]
    public function importBrands(Request $request): JsonResponse
    {
        $file = $request->files->get('file');

        if (!$file instanceof UploadedFile) {
            return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        if ($file->getClientOriginalExtension() !== 'xlsx') {
            return new JsonResponse(['error' => 'Invalid file format. Only XLSX allowed.'], Response::HTTP_BAD_REQUEST);
        }

        // Store the file temporarily
        $uploadsDir = $this->getParameter('kernel.project_dir') . '/var/uploads';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $filePath = $uploadsDir . '/' . uniqid('brands_', true) . '.xlsx';
        $file->move($uploadsDir, basename($filePath));

        // Process the XLSX file
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Remove headers
        array_shift($rows);

        foreach ($rows as $row) {
            [$name, $origin, $city, $yearStart, $yearEnd, $founders, $members, $musicGenre, $description] = $row;

            $genre = $this->musicGenreRepository->findOneBy(['name' => $musicGenre]);
            if (!empty($musicGenre) && !$genre) {
                return new JsonResponse(['error' => "Music genre '$musicGenre' not found"], Response::HTTP_BAD_REQUEST);
            }

            // Create brand using setters
            $brand = new Brand();
            $brand->setName($name);
            $brand->setOriginCountry($origin);
            $brand->setCity($city);
            $brand->setYearStart((int) $yearStart);
            $brand->setYearEnd($yearEnd ? (int) $yearEnd : null);
            $brand->setMusicGenre($genre);
            $brand->setDescription($description);

            $this->entityManager->persist($brand);
        }

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Brands imported successfully'], Response::HTTP_CREATED);
    }
}
