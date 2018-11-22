<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityTest
 * @package App\Tests
 */
final class SecurityFunctionalTest extends ApiTestCase
{
    public function setUp()
    {
        parent::setUp();
    }


    /**
     * @param string $uri
     * @param array $credentials
     *
     * @dataProvider loginProvider
     */
    public function test_authentication_return_token(string $uri, array $credentials)
    {
        $credentials = [
            'username' => $credentials [0],
            'password' => $credentials [1]
        ];

        $client = self::createClient();
        $client->request('POST', $uri, [], [], [], json_encode($credentials));
        $response = $client->getResponse();

        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
        static::assertArrayHasKey('token', json_decode($response->getContent(), true));
    }


    /**
     * @param string $uri
     * @param array $credentials
     *
     * @dataProvider badLoginProvider
     */
    public function test_bad_credentials_authentication_return_401(string $uri, array $credentials)
    {
        $credentials = [
            'username' => $credentials [0],
            'password' => $credentials [1]
        ];

        $client = self::createClient();
        $client->request('POST', $uri, [], [], [], json_encode($credentials));

        static::assertSame(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }



    /**
     * @param string $uri
     * @param string $method
     * @param $body
     *
     * @dataProvider AllRoutesProvider
     */
    public function test_protected_path_for_AUTHENTICATED_ANONYMOUSLY_returns_401(string $uri, string $method, $body)
    {
        $client = self::createClient();

        $client->request($method, $uri, [], [], [], $body);
        static::assertSame(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }



    /**
     * @param string $uri
     * @param string $method
     * @param string|null $body
     * @param array $credentials
     * @param int $expectedResponse
     *
     * @dataProvider roleSelfClientCanAccessRouteProvider
     */
    public function test_user_ROLE_SELF_CLIENT_can_access_route(string $uri, string $method, string $body = null, array $credentials, int $expectedResponse)
    {
        $client = $this->getAuthenticatedClient($credentials[0], $credentials[1]);

        $client->request($method, $uri, [], [], [], $body);

        static::assertSame($expectedResponse, $client->getResponse()->getStatusCode());
    }



    /**
     * @param string $uri
     * @param string $method
     * @param string|null $body
     *
     * @dataProvider roleSelfClientCanNOTAccessRouteProvider
     */
    public function test_user_granted_ROLE_SELF_CLIENT_can_not_access(string $uri, string $method, string $body = null)
    {
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
     * @dataProvider roleClientCanAccessProvider
     */
    public function test_role_user_can_access(string $uri, string $method)
    {
        $client = $this->getAuthenticatedClient('Client0', 'password0');
        $client->request($method, $uri);

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }


    /**
     * @param string $uri
     * @param string $method
     * @param string $body
     *
     * @dataProvider roleClientCanNOTAccessProvider
     */
    public function test_role_client_can_NOT_access(string $uri, string $method, string $body = null)
    {
        $client = $this->getAuthenticatedClient('Client2', 'password2');
        $client->request($method, $uri, [], [], [], $body);

        static::assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }


    /**
     * @param string $uri
     * @param string $method
     * @param string|null $body
     * @param int $expectedStatusCode
     *
     * @dataProvider AllRoutesProvider
     */
    public function test_role_company_can_access(string $uri, string $method, string $body = null, int $expectedStatusCode)
    {
        $client = $this->getAuthenticatedCompanyClient();
        $client->request($method, $uri, [], [], [], $body);

        if ($client->getResponse()->getStatusCode()===400) {
            dump(json_decode($client->getResponse()->getContent(), true));
        }
        static::assertSame($expectedStatusCode, $client->getResponse()->getStatusCode());
    }



    /**
     * @return \Generator
     */
    public function loginProvider()
    {
        yield ['/api/token/company', ['Bilemo', 'Bilemo']];
        yield ['/api/token/client', ['Client0', 'password0']];
    }


    /**
     * @return \Generator
     */
    public function badLoginProvider()
    {
        yield ['/api/token/company', ['Bilemo', 'BilemoAA']];
        yield ['/api/token/client', ['Client0', 'password0AA']];
    }


    /**
     * @return \Generator
     */
    public function AllRoutesProvider()
    {
        $phoneId = $this->getPhoneId();
        $clientId = $this->getClientId();

        $postPhone = [
            'manufacturer' => [
                'name' => 'Huawai'
            ],
            'model' => 'P1',
            'description' => 'The best cheapest phone',
            'price' => 399,
            'stock' => 32
        ];

        $putPhone = [
            'description' => 'The best smartphone',
            'price' => 99,
            'stock' => 42,
        ];

        $postClient = [
            'username' => 'clientForTest',
            'password' => 'passwordForTest',
            'email' => 'emailForTest@mail.com',
            'phoneNumber' => '0' . rand(000000000, 999999999)
        ];

        $putClient = [
            'password' => 'updatePassword',
            'email' => 'updateEmail@mail.com',
            'phoneNumber' => '0'. rand(000000000, 999999999)
        ];


        yield ['/api/phones',           'GET',      null                    , Response::HTTP_OK];
        yield ['/api/phones',           'POST',     json_encode($postPhone) , Response::HTTP_CREATED];
        yield ['/api/phones/'. $phoneId, 'GET',     null                    , Response::HTTP_OK];
        yield ['/api/phones/'. $phoneId, 'PUT',     json_encode($putPhone)  , Response::HTTP_OK];
        yield ['/api/phones/'. $phoneId, 'DELETE',  null                    , Response::HTTP_NO_CONTENT];
        yield ['/api/clients',            'GET',      null                    , Response::HTTP_OK];
        yield ['/api/clients/'. $clientId, 'GET',       null                    , Response::HTTP_OK];
        yield ['/api/clients',            'POST',     json_encode($postClient)  , Response::HTTP_CREATED];
        yield ['/api/clients/'. $clientId, 'PUT',       json_encode($putClient)   , Response::HTTP_OK];
        yield ['/api/clients/'. $clientId, 'DELETE',    null                    , Response::HTTP_NO_CONTENT];
    }


    /**
     * @return \Generator
     */
    public function roleSelfClientCanAccessRouteProvider()
    {
        $clientName = 'Client1';
        $password = 'password1';
        $updatePassword = 'updatePassword';
        $credentials = [$clientName, $password];

        $phoneId = $this->getPhoneId();
        $clientId = $this->getClientId($clientName);

        $putClient = [
            'password' => $updatePassword,
            'email' => 'updateEmail@mail.com',
            'phoneNumber' => '0'. rand(000000000, 999999999)
        ];

        yield ['/api/phones',               'GET',      null     , $credentials, Response::HTTP_OK];
        yield ['/api/phones/'. $phoneId,    'GET',      null     , $credentials, Response::HTTP_OK];
        yield ['/api/clients/'. $clientId,      'GET',      null     , $credentials, Response::HTTP_OK];
        yield ['/api/clients/'. $clientId,      'PUT',      json_encode($putClient), $credentials, Response::HTTP_OK];
        yield ['/api/clients/'. $clientId,      'DELETE',   null     , [$clientName, $updatePassword], Response::HTTP_NO_CONTENT];
    }


    /**
     * @return \Generator
     */
    public function roleSelfClientCanNOTAccessRouteProvider()
    {
        $phoneId = $this->getPhoneId();
        $clientId = $this->getClientId('Client0');

        yield ['/api/phones', 'POST', json_encode(['' => ''])];
        yield ['/api/phones/'. $phoneId, 'PUT', json_encode(['' => ''])];
        yield ['/api/phones/'. $phoneId, 'DELETE', null];
        yield ['/api/clients', 'GET', null];
        yield ['/api/clients', 'POST', json_encode(['' => ''])];
        yield ['/api/clients/'. $clientId, 'GET', null];
    }


    /**
     * @return \Generator
     */
    public function roleClientCanAccessProvider()
    {
        $phoneId = $this->getPhoneId();

        yield ['/api/phones', 'GET',];
        yield ['/api/phones/'. $phoneId, 'GET'];
    }


    /**
     * @return \Generator
     */
    public function roleClientCanNOTAccessProvider()
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
