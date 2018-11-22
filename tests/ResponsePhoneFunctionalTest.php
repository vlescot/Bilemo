<?php
declare(strict_types=1);

namespace App\Tests;

use App\Domain\Entity\Phone;
use Symfony\Component\HttpFoundation\Response;

final class ResponsePhoneFunctionalTest extends ApiTestCase
{
    public function testGETPhonesList()
    {
        $expectedContent = [
            'total' => 'int',
            'limit' => 'int',
            '_links' => [
                'first' => 'string',
                'self' => 'string',
                'next' => 'string',
                'last' => 'string',
            ],
            'objects' => [
                0 => [
                    'manufacturer' => [
                        'name' => 'string'
                    ],
                    'model' => 'string',
                    'description' => 'string',
                    '_links' => [
                        'self' => [
                            'href' => 'string'
                        ],
                    ],
                ]
            ],
        ];

        $this->assertResponse(
            'GET',
            '/api/phones',
            Response::HTTP_OK,
            'application/hal+json',
            $expectedContent
        );
    }

    public function testGETOnePhone()
    {
        $expectedContent = [
            'createdAt' => 'string',
            'manufacturer' => [
                'name' => 'string'
            ],
            'model' => 'string',
            'description' => 'string',
            'price' => 'int',
            'stock' => 'int',
            '_links' => [
                'self' => [
                    'href' => 'string'
                ],
            ],
        ];

        $this->assertResponse(
            'GET',
            '/api/phones/'. $this->getPhoneId(),
            Response::HTTP_OK,
            'application/hal+json',
            $expectedContent
        );
    }

    public function testPOSTPhone()
    {
        $manufacturerName = 'Nouveau Manufacturer';
        $model = 'Nouveau model';
        $description = 'Nouvelle description';
        $price = 19;
        $stock = 19;

        $phone = [
            'manufacturer' => [
                'name' => $manufacturerName
            ],
            'model' => $model,
            'description' => $description,
            'price' => $price,
            'stock' => $stock,
        ];

        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('POST', '/api/phones', [], [], [], json_encode($phone));

        static::assertSame(Response::HTTP_CREATED, $kernelClient->getResponse()->getStatusCode());
        $phone = $this->findBy(Phone::class, ['model' => $model]);

        static::assertSame($manufacturerName, $phone->getManufacturer()->getName());
        static::assertSame($model, $phone->getModel());
        static::assertSame($description, $phone->getDescription());
        static::assertSame($price, $phone->getPrice());
        static::assertSame($stock, $phone->getStock());
    }

    public function testPUTPhone()
    {
        $model = 'Nouveau model';
        $description = 'Description mise à jour';
        $price = 99;
        $stock = 99;

        $phone = [
            'description' => $description,
            'price' => $price,
            'stock' => $stock
        ];

        $phoneId = $this->getPhoneId($model);

        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('PUT', '/api/phones/'. $phoneId, [], [], [], json_encode($phone));

        static::assertSame(Response::HTTP_OK, $kernelClient->getResponse()->getStatusCode());
        $phone = $this->findBy(Phone::class, ['model' => $model]);

        static::assertSame($model, $phone->getModel());
        static::assertSame($description, $phone->getDescription());
        static::assertSame($price, $phone->getPrice());
        static::assertSame($stock, $phone->getStock());
    }

    public function testDELETEPhone()
    {
        $model = 'Nouveau model';
        $phoneId = $this->getPhoneId($model);

        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('DELETE', '/api/phones/'. $phoneId);

        static::assertEquals(Response::HTTP_NO_CONTENT, $kernelClient->getResponse()->getStatusCode());

        $kernelClient->request('GET', '/api/phones/'. $phoneId);
        static::assertEquals(Response::HTTP_NOT_FOUND, $kernelClient->getResponse()->getStatusCode());
    }
}
