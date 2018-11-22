<?php
declare(strict_types=1);

namespace App\Tests;

use App\Domain\Entity\Client;
use Symfony\Component\HttpFoundation\Response;

final class ResponseClientFunctionalTest extends ApiTestCase
{
    public function testGETClientsList()
    {
        $expectedContent = [
            0 => [
                'username' => 'string',
                'email' => 'string',
                'createdAt' => 'string',
                '_links' => [
                    'self' => [
                        'href' => 'string'
                    ],
                ],
            ]
        ];

        $this->assertResponse(
            'GET',
            '/api/clients',
            Response::HTTP_OK,
            'application/hal+json',
            $expectedContent
        );
    }

    public function testGETOneClient()
    {
        $expectedContent = [
            'username' => 'string',
            'email' => 'string',
            'phoneNumber' => 'string',
            '_links' => [
                'self' => [
                    'href' => 'string'
                ],
            ],
        ];

        $this->assertResponse(
            'GET',
            '/api/clients/'. $this->getClientId(),
            Response::HTTP_OK,
            'application/hal+json',
            $expectedContent
        );
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
