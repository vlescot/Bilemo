<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;

final class SecurityClientFunctionalTest extends ApiTestCase
{
    /**
     * @param string $uri
     * @param string $method
     * @param string|null $body
     * @param array $credentials
     * @param int $expectedResponse
     *
     * @dataProvider ROLE_SELF_CLIENT_can_access_provider
     */
    public function test_ROLE_SELF_CLIENT_can_access(
        string $uri,
        string $method,
        string $body = null,
        array $credentials,
        int $expectedResponse
    ) {
        $client = $this->getAuthenticatedClient($credentials[0], $credentials[1]);

        $client->request($method, $uri, [], [], [], $body);

        static::assertSame($expectedResponse, $client->getResponse()->getStatusCode());
    }



    /**
     * @param string $uri
     * @param string $method
     * @param string|null $body
     *
     * @dataProvider ROLE_SELF_CLIENT_can_NOT_access_provider
     */
    public function test_ROLE_SELF_CLIENT_can_not_access(
        string $uri,
        string $method,
        string $body = null
    ) {
        $username = 'Client2';
        $password = 'password2';

        $client = $this->getAuthenticatedClient($username, $password);
        $client->request($method, $uri, [], [], [], $body);

        static::assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }


    /**
     * @param string $uri
     * @param string $method
     *
     * @dataProvider ROLE_CLIENT_can_access_provider
     */
    public function test_ROLE_CLIENT_can_access(
        string $uri,
        string $method
    ) {
        $client = $this->getAuthenticatedClient('Client0', 'password0');
        $client->request($method, $uri);

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }


    /**
     * @param string $uri
     * @param string $method
     * @param string $body
     *
     * @dataProvider ROLE_CLIENT_can_NOT_access_provider
     */
    public function test_ROLE_CLIENT_can_NOT_access(
        string $uri,
        string $method,
        string $body = null
    ) {
        $client = $this->getAuthenticatedClient('Client2', 'password2');
        $client->request($method, $uri, [], [], [], $body);

        static::assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }


    /**
     * @return \Generator
     */
    public function ROLE_SELF_CLIENT_can_access_provider()
    {
        $clientName = 'Client3';
        $password = 'password3';
        $updatePassword = 'updatePassword';
        $credentials = [$clientName, $password];

        $phoneId = $this->getPhoneId();
        $clientId = $this->getClientId($clientName);

        $putClient = [
            'password' => $updatePassword,
            'email' => 'updateEmail@mail.com',
            'phoneNumber' => '0'. rand(000000000, 999999999)
        ];

        $userId = $this->getUserClientId('Client3');

        $postUser = [
            'name' => 'userTest',
            'phoneNumber' => '0566778899',
            'email' => 'userTest@mail.com',
            'address' => [
                'streetAddress' => 'addressTest street',
                'city' => 'cityTest',
                'postcode' => 99999,
            ]
        ];

        $putUser = [
            'phoneNumber' => '0988776655',
            'email' => 'updatedEmail@mail.com',
            'address' => [
                'streetAddress' => 'updated street',
                'city' => 'Uptown',
                'postcode' => 42000
            ]
        ];

        yield ['/api/phones',               'GET',      null,                   $credentials, Response::HTTP_OK];
        yield ['/api/phones/'. $phoneId,    'GET',      null,                   $credentials, Response::HTTP_OK];
        yield['/api/users'          ,       'GET',      null,                   $credentials, Response::HTTP_OK];
        yield['/api/users/'. $userId,       'GET',      null,                   $credentials, Response::HTTP_OK];
        yield['/api/users'          ,       'POST',     json_encode($postUser), $credentials, Response::HTTP_CREATED];
        yield['/api/users/'. $userId,       'PUT',      json_encode($putUser),  $credentials, Response::HTTP_OK];
        yield['/api/users/'. $userId,       'DELETE',   null,                   $credentials, Response::HTTP_NO_CONTENT];
        yield ['/api/clients/'. $clientId,  'GET',      null,                   $credentials, Response::HTTP_OK];
        yield ['/api/clients/'. $clientId,  'PUT',      json_encode($putClient), $credentials, Response::HTTP_OK];
        yield ['/api/clients/'. $clientId,  'DELETE',   null,                   [$clientName, $updatePassword], Response::HTTP_NO_CONTENT];
    }


    /**
     * @return \Generator
     */
    public function ROLE_SELF_CLIENT_can_NOT_access_provider()
    {
        $phoneId = $this->getPhoneId();
        $clientId = $this->getClientId('Client0');
        $userId = $this->getUserClientId('Client1');

        $putUser = [
            'phoneNumber' => '0988776655',
            'email' => 'updatedEmail@mail.com',
            'address' => [
                'streetAddress' => 'updated street',
                'city' => 'Uptown',
                'postcode' => 42000
            ]
        ];
        yield ['/api/phones', 'POST', json_encode(['' => ''])];
        yield ['/api/phones/'. $phoneId, 'PUT', json_encode(['' => ''])];
        yield ['/api/phones/'. $phoneId, 'DELETE', null];
        yield ['/api/clients', 'GET', null];
        yield ['/api/clients', 'POST', json_encode(['' => ''])];
        yield ['/api/clients/'. $clientId, 'GET', null];
        yield['/api/users-list',        'GET',    null];
        yield['/api/users/'. $userId,   'GET',    null];
        yield['/api/users/'. $userId,   'PUT',    json_encode($putUser)];
        yield['/api/users/'. $userId,   'DELETE', null];
    }


    /**
     * @return \Generator
     */
    public function ROLE_CLIENT_can_access_provider()
    {
        $phoneId = $this->getPhoneId();

        yield ['/api/phones', 'GET',];
        yield ['/api/phones/'. $phoneId, 'GET'];
    }


    /**
     * @return \Generator
     */
    public function ROLE_CLIENT_can_NOT_access_provider()
    {
        $phoneId = $this->getPhoneId();
        $clientId = $this->getClientId('Client0');

        yield ['/api/phones',               'POST',     json_encode(['' => '']) ];
        yield ['/api/phones/'. $phoneId,    'PUT',      json_encode(['' => '']) ];
        yield ['/api/phones/'. $phoneId,    'DELETE',   null ];
        yield ['/api/clients',                'GET',      null ];
        yield ['/api/clients',                'POST',     json_encode(['' => '']) ];
        yield ['/api/clients/'. $clientId,      'GET',      null ];
        yield ['/api/clients/'. $clientId,      'PUT',      json_encode(['' => '']) ];
        yield ['/api/clients/'. $clientId,      'DELETE',   null ];
    }
}
