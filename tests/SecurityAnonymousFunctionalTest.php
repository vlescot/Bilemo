<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;

final class SecurityAnonymousFunctionalTest extends ApiTestCase
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
}
