<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityTest
 * @package App\Tests
 */
final class SecurityTest extends ApiTestCase
{
    public function login_paths_is_free_access()
    {
        $client = self::createClient();

        $client->request('GET', '/user/token');
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $client->request('GET', '/company/token');
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }


    public function test_protected_path_returns_unauthorized()
    {
        $client = self::createClient();

        $client->request('GET', '/user');
        static::assertSame(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());

        $client->request('GET', '/phone');
        static::assertSame(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }


    /**
     * @dataProvider credentialsProvider
     */
    public function test_authentication(string $username, string $password)
    {
        $this->createUser($username, $password);

        $content  = json_encode([
            'username' => $username,
            'password' => $password
        ]);


        $client = static::createClient();
        $client->request(
            'POST',
            'user/token',
            [],
            [],
            [],
            $content
        );
        $data = json_decode($client->getResponse()->getContent(), true);

        static::assertArrayHasKey('token', $data);
    }


    /**
     * @param string $username
     * @param string $password
     *
     * @dataProvider credentialsProvider
     */
    public function test_authentication_with_bad_credentials(string $username, string $password)
    {
        $this->createUser($username, $password);

        $content  = json_encode([
            'username' => $username,
            'password' => 'ivoivzevoivnsi'
        ]);


        $client = static::createClient();
        $client->request(
            'POST',
            'user/token',
            [],
            [],
            [],
            $content
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        static::assertSame(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
        static::assertContains('Bad credentials', $data['message']);
    }


    /**
     * @param string $username
     * @param string $password
     *
     * @dataProvider RoutesAllowedForUserProvider
     */
    public function test_user_granted_ROLE_SELF_USER_can_access_route(string $route, string $method, string $username = null, string $password = null, string $expectedResponse)
    {
        $this->createUser($username, $password);

        $client = $this->createAuthenticatedUser($username, $password);

        $client->request($method, $route);
        static::assertSame($expectedResponse, $client->getResponse()->getStatusCode());
    }


    /**
     * @param string $route
     * @param string $method
     *
     * @dataProvider RoutesNotAllowedForUserProvider
     */
    public function test_authenticated_user_not_allowed_get_routes(string $route, string $method)
    {
        $username = 'fakeUsername';
        $password= 'fakePassword';

        $this->createUser($username, $password);

        $client = $this->createAuthenticatedUser($username, $password);

        $client->request($method, $route);
        static::assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }


    /**
     * @param string $route
     * @param string $method
     *
     * @dataProvider AllRoutes
     */
    public function test_authenticated_company_can_get_routes(string $route, string $method, string $content = null)
    {
        $client = $this->createAuthenticatedCompany();
        $client->request($method, $route, [], [], [], $content . PHP_EOL);

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }


    /**
     * @param string $username
     * @param string $password
     *
     * @dataProvider credentialsProvider
     */
    public function test_user_granted_ROLE_SELF_USER_can_access(string $username, string $password)
    {
        $client = $this->createAuthenticatedUser($username, $password);
        $client->request('GET', '/user/'. $username);
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $client = $this->createAuthenticatedUser($username, $password);
        $client->request('PUT', '/user/'. $username);
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $client = $this->createAuthenticatedUser($username, $password);
        $client->request('DELETE', '/user/'. $username);
        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }


    /**
     * @param string $username
     * @param string $password
     *
     * @dataProvider credentialsProvider
     */
    public function test_user_granted_ROLE_SELF_USER_can_not_access(string $username, string $password)
    {
        $notAllowedUsername = 'fakeUsername1';
        $this->createUser($notAllowedUsername, 'fakePassword1');

        $client = $this->createAuthenticatedUser($username, $password);
        $client->request('GET', '/user/'. $notAllowedUsername);
        static::assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());

        $client = $this->createAuthenticatedUser($username, $password);
        $client->request('PUT', '/user/'. $notAllowedUsername);
        static::assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());

        $client = $this->createAuthenticatedUser($username, $password);
        $client->request('DELETE', '/user/'. $notAllowedUsername);
        static::assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }


    /**
     * @return \Generator
     */
    public function credentialsProvider()
    {
        yield ['fakeUsername', 'fakePassword'];
    }

    /**
     * @return \Generator
     */
    public function RoutesAllowedForUserProvider()
    {
        $username = 'fakeUsername0';
        $password = 'fakePassword0';

        yield ['/phone', 'GET', null, null, Response::HTTP_OK];
        yield ['/phone/Samsung-S100', 'GET', null, null, Response::HTTP_OK];
        yield ['/user/'. $username, 'GET', $username, $password, Response::HTTP_OK];
//        yield ['/user/'. $username, 'PUT', $username, $password, Response::??];
//        yield ['/user/'. $username, 'DELETE', $username, $password, Response::??];
    }


    /**
     * @return \Generator
     */
    public function RoutesNotAllowedForUserProvider()
    {
        $username = 'fakeUsername0';

        yield ['/phone', 'POST'];
//        yield ['/phone/Samsung-S100', 'PUT'];
//        yield ['/phone/Samsung-S100', 'DELETE'];
        yield ['/user', 'GET'];
//        yield ['/user', 'POST'];
        yield ['/user/'. $username, 'GET'];
        yield ['/user/'. $username, 'PUT'];
        yield ['/user/'. $username, 'DELETE'];
    }


    /**
     * @return \Generator
     */
    public function AllRoutes()
    {
        $createPhoneData = [
            'brand' => 'Huawai',
            'model' => 'P1',
            'description' => 'The best cheapest phone',
            'price' => 399,
            'stock' => 32
        ];

        yield ['/phone', 'GET', null];
        yield ['/phone', 'POST', json_encode($createPhoneData)];
        yield ['/phone/Samsung-S100', 'GET', null];
//        yield ['/phone/Samsung-S100', 'PUT', NOTNULL];
//        yield ['/phone/Samsung-S100', 'DELETE', null];
        yield ['/user', 'GET', null];
        yield ['/user/user0', 'GET', null];
//        yield ['/user', 'POST', NOTNULL];
//        yield ['/user/user0', 'PUT', NOTNULL];
//        yield ['/user/user0', 'DELETE', NOTNULL];
    }
}
