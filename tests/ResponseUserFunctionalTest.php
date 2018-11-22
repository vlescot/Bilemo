<?php
declare(strict_types=1);

namespace App\Tests;

use App\Domain\Entity\User;
use Symfony\Component\HttpFoundation\Response;

final class ResponseUserFunctionalTest extends ApiTestCase
{
    public function test_GET_users_list()
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
                    'name' => 'string',
                    'address' => [
                        'city' => 'string',
                        'postcode' => 'int'
                    ],
                    'client' => [
                        'username' => 'string'
                    ],
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
            '/api/users',
            Response::HTTP_OK,
            'application/hal+json',
            $expectedContent
        );
    }


    public function test_GET_one_user()
    {
        $expectedContent = [
            'createdAt' => 'string',
            'name' => 'string',
            'phoneNumber' => 'string',
            'address' => [
                'streetAddress' => 'string',
                'city' => 'string',
                'postcode' => 'int'
            ],
            'client' => [
                'username' => 'string'
            ],
            '_links' => [
                'self' => [
                    'href' => 'string'
                ],
            ],
        ];

        $this->assertResponse(
            'GET',
            '/api/users/'. $this->getUserId(),
            Response::HTTP_OK,
            'application/hal+json',
            $expectedContent
        );
    }


    public function test_POST_user()
    {
        $user = [
            'name' => 'userTest',
            'phoneNumber' => '0566778899',
            'email' => 'userTest@mail.com',
            'address' => [
                'streetAddress' => 'addressTest street',
                'city' => 'cityTest',
                'postcode' => 99999,
            ],
            'client' => [
                'username' => 'Client0'
            ]
        ];

        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('POST', '/api/users', [], [], [], json_encode($user));
        $userEntity = $this->findBy(User::class, ['name' => $user['name'] ]);

        static::assertSame(Response::HTTP_CREATED, $kernelClient->getResponse()->getStatusCode());
        static::assertSame($user['name'], $userEntity->getName());
        static::assertSame($user['phoneNumber'], $userEntity->getPhoneNumber());
        static::assertSame($user['email'], $userEntity->getEmail());
        static::assertSame($user['address']['streetAddress'], $userEntity->getAddress()->getStreetAddress());
        static::assertSame($user['address']['city'], $userEntity->getAddress()->getCity());
        static::assertSame($user['address']['postcode'], $userEntity->getAddress()->getPostcode());
        static::assertSame($user['client']['username'], $userEntity->getClient()->getUsername());
    }

    public function test_PUT_user()
    {
        $user = [
            'phoneNumber' => '0988776655',
            'email' => 'updatedEmail@mail.com',
            'address' => [
                'streetAddress' => 'updated street',
                'city' => 'Uptown',
                'postcode' => 42000
            ]
        ];

        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('PUT', '/api/users/'. $this->getUserId('userTest'), [], [], [], json_encode($user));
        $userEntity = $this->findBy(User::class, ['name' => 'userTest' ]);

        static::assertSame(Response::HTTP_OK, $kernelClient->getResponse()->getStatusCode());
        static::assertSame($user['phoneNumber'], $userEntity->getPhoneNumber());
        static::assertSame($user['email'], $userEntity->getEmail());
        static::assertSame($user['address']['streetAddress'], $userEntity->getAddress()->getStreetAddress());
        static::assertSame($user['address']['city'], $userEntity->getAddress()->getCity());
        static::assertSame($user['address']['postcode'], $userEntity->getAddress()->getPostcode());
    }

    public function test_DELETE_user()
    {
        $userId = $this->getUserId('userTest');

        $kernelClient = $this->getAuthenticatedCompanyClient();
        $kernelClient->request('DELETE', '/api/users/'. $userId);

        static::assertEquals(Response::HTTP_NO_CONTENT, $kernelClient->getResponse()->getStatusCode());

        $kernelClient->request('GET', '/api/users/'. $userId);
        static::assertEquals(Response::HTTP_NOT_FOUND, $kernelClient->getResponse()->getStatusCode());
    }
}
