<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportBrandsControllerTest extends WebTestCase
{
    public function testImportBrandsSuccess(): void
    {
        $client = static::createClient();

        $file = new UploadedFile(
            __DIR__ . '/test_brands.xlsx', // Place a valid XLSX file in tests/Controller
            'test_brands.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $client->request(
            'POST',
            '/api/import-brands',
            [],
            ['file' => $file],
            ['CONTENT_TYPE' => 'multipart/form-data']
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonStringEqualsJsonString(
            json_encode(['message' => 'Brands imported successfully']),
            $client->getResponse()->getContent()
        );
    }
}
