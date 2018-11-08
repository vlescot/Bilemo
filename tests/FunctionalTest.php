<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityTest
 * @package App\Tests
 */
final class FunctionalTest extends ApiTestCase
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
        $client->request('POST',  $uri, [], [], [], json_encode($credentials));
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
        $client->request('POST',  $uri, [], [], [], json_encode($credentials));

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
     * @dataProvider roleSelfUserCanAccessRouteProvider
     */
    public function test_user_ROLE_SELF_USER_can_access_route(string $uri, string $method, string $body = null, array $credentials, int $expectedResponse)
    {
        $client = $this->createAuthenticatedUser($credentials[0], $credentials[1]);

        $client->request($method, $uri, [] , [] , [] , $body);

        static::assertSame($expectedResponse, $client->getResponse()->getStatusCode());
    }


    /**
     * @param string $uri
     * @param string $method
     * @param string|null $body
     *
     * @dataProvider roleSelfUserCanNOTAccessRouteProvider
     */
    public function test_user_granted_ROLE_SELF_USER_can_not_access(string $uri, string $method, string $body = null)
    {
        $username = 'User2';
        $password = 'password2';

        $client = $this->createAuthenticatedUser($username, $password);
        $client->request($method, $uri, [] , [] , [] , $body);

        static::assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $uri
     * @param string $method
     *
     * @dataProvider roleUserCanAccessProvider
     */
    public function test_role_user_can_access(string $uri, string $method)
    {
        $client = $this->createAuthenticatedUser('User0', 'password0');
        $client->request($method, $uri);

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $uri
     * @param string $method
     * @param string $body
     *
     * @dataProvider roleUserCanNOTAccessProvider
     */
    public function test_role_user_can_NOT_access(string $uri, string $method, string $body = null)
    {
        $client = $this->createAuthenticatedUser('User2', 'password2');
        $client->request($method, $uri, [] , [] , [] , $body);

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
        $client = $this->createAuthenticatedCompany();
        $client->request($method, $uri, [] , [] , [] , $body);

        static::assertSame($expectedStatusCode, $client->getResponse()->getStatusCode());
    }


    /**
     * @return \Generator
     */
    public function loginProvider()
    {
        yield ['/api/token/company', ['Bilemo', 'Bilemo']];
        yield ['/api/token/user', ['User0', 'password0']];
    }

    /**
     * @return \Generator
     */
    public function badLoginProvider()
    {
        yield ['/api/token/company', ['Bilemo', 'BilemoAA']];
        yield ['/api/token/user', ['User0', 'password0AA']];
    }

    /**
     * @return \Generator
     */
    public function AllRoutesProvider()
    {
        $phoneId = $this->getPhoneId();
        $userId = $this->getUserId();

        $postPhone = [
            'brand' => 'Huawai',
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

        $postUser = [
            'username' => 'userForTest',
            'password' => 'passwordForTest',
            'email' => 'emailForTest@mail.com',
        ];

        $putUser = [
            'password' => 'updatePassword',
            'email' => 'updateEmail@mail.com',
        ];


        yield ['/api/phones',           'GET',      null                    , Response::HTTP_OK];
        yield ['/api/phones',           'POST',     json_encode($postPhone) , Response::HTTP_CREATED];
        yield ['/api/phones/'. $phoneId, 'GET',     null                    , Response::HTTP_OK];
        yield ['/api/phones/'. $phoneId, 'PUT',     json_encode($putPhone)  , Response::HTTP_OK];
        yield ['/api/phones/'. $phoneId, 'DELETE',  null                    , Response::HTTP_NO_CONTENT];
        yield ['/api/users',            'GET',      null                    , Response::HTTP_OK];
        yield ['/api/users/'. $userId, 'GET',       null                    , Response::HTTP_OK];
        yield ['/api/users',            'POST',     json_encode($postUser)  , Response::HTTP_CREATED];
        yield ['/api/users/'. $userId, 'PUT',       json_encode($putUser)   , Response::HTTP_OK];
        yield ['/api/users/'. $userId, 'DELETE',    null                    , Response::HTTP_NO_CONTENT];
    }

    /**
     * @return \Generator
     */
    public function roleSelfUserCanAccessRouteProvider()
    {
        $username = 'User1';
        $password = 'password1';
        $updatePassword = 'updatePassword';
        $credentials = [$username, $password];

        $phoneId = $this->getPhoneId();
        $userId = $this->getUserId($username);


        $putUser = [
            'password' => $updatePassword,
            'email' => 'updateEmail@mail.com',
        ];

        yield ['/api/phones',               'GET',      null     , $credentials, Response::HTTP_OK];
        yield ['/api/phones/'. $phoneId,    'GET',      null     , $credentials, Response::HTTP_OK];
        yield ['/api/users/'. $userId,      'GET',      null     , $credentials, Response::HTTP_OK];
        yield ['/api/users/'. $userId,      'PUT',      json_encode($putUser), $credentials, Response::HTTP_OK];
        yield ['/api/users/'. $userId,      'DELETE',   null     , [$username, $updatePassword], Response::HTTP_NO_CONTENT];
    }

    /**
     * @return \Generator
     */
    public function roleSelfUserCanNOTAccessRouteProvider()
    {
        $phoneId = $this->getPhoneId();
        $userId = $this->getUserId('User0');

        yield ['/api/phones', 'POST', json_encode(['' => ''])];
        yield ['/api/phones/'. $phoneId, 'PUT', json_encode(['' => ''])];
        yield ['/api/phones/'. $phoneId, 'DELETE', null];
        yield ['/api/users', 'GET', null];
        yield ['/api/users', 'POST', json_encode(['' => ''])];
        yield ['/api/users/'. $userId, 'GET', null];
    }

    /**
     * @return \Generator
     */
    public function roleUserCanAccessProvider()
    {
        $phoneId = $this->getPhoneId();

        yield ['/api/phones', 'GET',];
        yield ['/api/phones/'. $phoneId, 'GET'];
    }

    /**
     * @return \Generator
     */
    public function roleUserCanNOTAccessProvider()
    {
        $phoneId = $this->getPhoneId();
        $userId = $this->getUserId('User0');

        yield ['/api/phones',               'POST',     json_encode(['' => '']) ];
        yield ['/api/phones/'. $phoneId,    'PUT',      json_encode(['' => '']) ];
        yield ['/api/phones/'. $phoneId,    'DELETE',   null ];
        yield ['/api/users',                'GET',      null ];
        yield ['/api/users',                'POST',     json_encode(['' => '']) ];
        yield ['/api/users/'. $userId,      'GET',      null ];
        yield ['/api/users/'. $userId,      'PUT',      json_encode(['' => '']) ];
        yield ['/api/users/'. $userId,      'DELETE',   null ];
    }
}