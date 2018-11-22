<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class SecurityTest
 * @package App\Tests
 */
final class SecurityCompanyFunctionalTest extends ApiTestCase
{
    public function setUp()
    {
        parent::setUp();
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
