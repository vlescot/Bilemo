<?php
declare(strict_types=1);

namespace App\Tests;

use App\Domain\Entity\Phone;
use App\Domain\Entity\User;
use Symfony\Component\HttpFoundation\Response;

final class ResponseContentFunctionalTest extends ApiTestCase
{
    public function testGETPhonesList()
    {
        $client = $this->getAuthenticatedCompanyClient();
        $client->request('GET', '/api/phones');

        $responseContent = json_decode($client->getResponse()->getContent(), true);

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

        static::assertSame('application/hal+json', $client->getResponse()->headers->get('Content-Type'));
        $this->assertResponseContent($expectedContent, $responseContent);
    }

    public function testGETUsersList()
    {
        $client = $this->getAuthenticatedCompanyClient();
        $client->request('GET', '/api/users');

        $responseContent = json_decode($client->getResponse()->getContent(), true);

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

        static::assertSame('application/hal+json', $client->getResponse()->headers->get('Content-Type'));
        $this->assertResponseContent($expectedResponse, $responseContent);
    }

    public function testGETOnePhone()
    {
        $client = $this->getAuthenticatedCompanyClient();
        $client->request('GET', '/api/phones/'. $this->getPhoneId());

        $responseContent = json_decode($client->getResponse()->getContent(), true);

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

        static::assertSame('application/hal+json', $client->getResponse()->headers->get('Content-Type'));
        $this->assertResponseContent($expectedResponse, $responseContent);
    }

    public function testGETOneUser()
    {
        $client = $this->getAuthenticatedCompanyClient();
        $client->request('GET', '/api/users/'. $this->getUserId());

        $responseContent = json_decode($client->getResponse()->getContent(), true);

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

        static::assertSame('application/hal+json', $client->getResponse()->headers->get('Content-Type'));
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

        $client = $this->getAuthenticatedCompanyClient();
        $client->request('POST', '/api/phones', [], [], [], json_encode($phone));

        static::assertSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $phone = $this->getEntityBy(Phone::class, ['model' => $model]);

        static::assertSame($manufacturerName, $phone->getManufacturer()->getName());
        static::assertSame($model, $phone->getModel());
        static::assertSame($description, $phone->getDescription());
        static::assertSame($price, $phone->getPrice());
        static::assertSame($stock, $phone->getStock());
    }

    public function testPOSTUser()
    {
        $username = 'usernameTest';
        $password = 'passwordTest';
        $email = 'usernameTest@mail.com';
        $phoneNumber = '0655443322';

        $user = [
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'phoneNumber' => $phoneNumber
        ];

        $client = $this->getAuthenticatedCompanyClient();
        $client->request('POST', '/api/users', [], [], [], json_encode($user));

        static::assertSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $user = $this->getEntityBy(User::class, ['username' => $username]);

        static::assertSame($username, $user->getUsername());
        static::assertNotSame($password, $user->getPassword());
        static::assertSame($email, $user->getEmail());
        static::assertSame($phoneNumber, $user->getPhoneNumber());
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

        $client = $this->getAuthenticatedCompanyClient();
        $client->request('PUT', '/api/phones/'. $phoneId, [], [], [], json_encode($phone));

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $phone = $this->getEntityBy(Phone::class, ['model' => $model]);

        static::assertSame($model, $phone->getModel());
        static::assertSame($description, $phone->getDescription());
        static::assertSame($price, $phone->getPrice());
        static::assertSame($stock, $phone->getStock());
    }

    public function testPUTUser()
    {
        $username = 'usernameTest';
        $password = 'updatedPassword';
        $email = 'updateUser@mail.com';
        $phoneNumber = '0566778899';

        $user = [
            'password' => $password,
            'email' => $email,
            'phoneNumber' => $phoneNumber
        ];

        $userId = $this->getUserId($username);

        $client = $this->getAuthenticatedCompanyClient();
        $client->request('PUT', '/api/users/'. $userId, [], [], [], json_encode($user));

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $user = $this->getEntityBy(User::class, ['username' => $username]);

        static::assertNotSame($password, $user->getPassword());
        static::assertSame($email, $user->getEmail());
        static::assertSame($phoneNumber, $user->getPhoneNumber());
    }

    public function testDELETEPhone()
    {
        $model = 'Nouveau model';
        $phoneId = $this->getPhoneId($model);

        $client = $this->getAuthenticatedCompanyClient();
        $client->request('DELETE', '/api/phones/'. $phoneId);

        static::assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        $client->request('GET', '/api/phones/'. $phoneId);
        static::assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testDELETEUser()
    {
        $username = 'usernameTest';
        $userId = $this->getUserId($username);

        $client = $this->getAuthenticatedCompanyClient();
        $client->request('DELETE', '/api/users/'. $userId);

        static::assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());

        $client->request('GET', '/api/users/'. $userId);
        static::assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }
}