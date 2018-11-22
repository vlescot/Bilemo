<?php
declare(strict_types=1);

namespace App\Tests;

use App\Domain\Entity\Phone;
use App\Domain\Entity\Client;
use Symfony\Component\HttpFoundation\Response;

final class ResponseContentFunctionalTest extends ApiTestCase
{
    public function testGETPhonesList()
    {
        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('GET', '/api/phones');

        $responseContent = json_decode($kernelClient->getResponse()->getContent(), true);

        $expectedContent = [
            'total' => 'int',
            'limit' => 'int',
            '_links' => [
                'first' => 'string',
                'self' => 'string',
                'next' => 'string',
                'last' => 'string',
            ],
            'phones' => [
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
                        'update' => [
                            'href' => 'string'
                        ],
                        'delete' => [
                            'href' => 'string'
                        ]
                    ],
                ]
            ],
        ];

        static::assertSame('application/hal+json', $kernelClient->getResponse()->headers->get('Content-Type'));
        $this->assertResponseContent($expectedContent, $responseContent);
    }

    public function testGETClientsList()
    {
        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('GET', '/api/clients');

        $responseContent = json_decode($kernelClient->getResponse()->getContent(), true);

        $expectedResponse = [
            0 => [
                'username' => 'string',
                'email' => 'string',
                'createdAt' => 'string',
                '_links' => [
                    'self' => [
                        'href' => 'string'
                    ],
                    'update' => [
                        'href' => 'string'
                    ],
                    'delete' => [
                        'href' => 'string'
                    ]
                ],
            ]
        ];

        static::assertSame('application/hal+json', $kernelClient->getResponse()->headers->get('Content-Type'));
        $this->assertResponseContent($expectedResponse, $responseContent);
    }

    public function testGETOnePhone()
    {
        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('GET', '/api/phones/'. $this->getPhoneId());

        $responseContent = json_decode($kernelClient->getResponse()->getContent(), true);

        $expectedResponse = [
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
                'update' => [
                    'href' => 'string'
                ],
                'delete' => [
                    'href' => 'string'
                ]
            ],
        ];

        static::assertSame('application/hal+json', $kernelClient->getResponse()->headers->get('Content-Type'));
        $this->assertResponseContent($expectedResponse, $responseContent);
    }

    public function testGETOneClient()
    {
        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('GET', '/api/clients/'. $this->getClientId());

        $responseContent = json_decode($kernelClient->getResponse()->getContent(), true);

        $expectedContent = [
            'username' => 'string',
            'email' => 'string',
            'phoneNumber' => 'string',
            '_links' => [
                'self' => [
                    'href' => 'string'
                ],
                'update' => [
                    'href' => 'string'
                ],
                'delete' => [
                    'href' => 'string'
                ]
            ],
        ];

        static::assertSame('application/hal+json', $kernelClient->getResponse()->headers->get('Content-Type'));
        $this->assertResponseContent($expectedContent, $responseContent);
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

    public function testPOSTClient()
    {
        $username = 'usernameTest';
        $password = 'passwordTest';
        $email = 'usernameTest@mail.com';
        $phoneNumber = '0655443322';

        $client = [
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'phoneNumber' => $phoneNumber
        ];

        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('POST', '/api/clients', [], [], [], json_encode($client));

        static::assertSame(Response::HTTP_CREATED, $kernelClient->getResponse()->getStatusCode());
        $client = $this->findBy(Client::class, ['username' => $username]);

        static::assertSame($username, $client->getUsername());
        static::assertNotSame($password, $client->getPassword());
        static::assertSame($email, $client->getEmail());
        static::assertSame($phoneNumber, $client->getPhoneNumber());
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

    public function testPUTClient()
    {
        $username = 'usernameTest';
        $password = 'updatedPassword';
        $email = 'updateClient@mail.com';
        $phoneNumber = '0566778899';

        $client = [
            'password' => $password,
            'email' => $email,
            'phoneNumber' => $phoneNumber
        ];

        $clientId = $this->getClientId($username);

        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('PUT', '/api/clients/'. $clientId, [], [], [], json_encode($client));

        static::assertSame(Response::HTTP_OK, $kernelClient->getResponse()->getStatusCode());
        $client = $this->findBy(Client::class, ['username' => $username]);
        static::assertNotSame($password, $client->getPassword());
        static::assertSame($email, $client->getEmail());
        static::assertSame($phoneNumber, $client->getPhoneNumber());
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

    public function testDELETEClient()
    {
        $username = 'usernameTest';
        $clientId = $this->getClientId($username);

        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('DELETE', '/api/clients/'. $clientId);

        static::assertEquals(Response::HTTP_NO_CONTENT, $kernelClient->getResponse()->getStatusCode());

        $kernelClient->request('GET', '/api/clients/'. $clientId);
        static::assertEquals(Response::HTTP_NOT_FOUND, $kernelClient->getResponse()->getStatusCode());
    }
}