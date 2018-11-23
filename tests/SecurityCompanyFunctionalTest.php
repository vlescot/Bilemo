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
        $userId = $this->getUserClientId();

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

        yield['/api/users-list',        'GET',      null                    , Response::HTTP_OK];
        yield['/api/users/'. $userId,   'GET',      null                    , Response::HTTP_OK];
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
     * @param string $uri
     * @param string $method
     * @param string $content
     *
     * @dataProvider ROLE_COMPANY_can_NOT_access_provider
     */
    public function test_try_ROLE_COMPANY_can_NOT_access(
        string $uri,
        string $method,
        string $content = null
    ) {
        $kernelClient = $this->getAuthenticatedCompanyClient();

        $kernelClient->request($method, $uri, [], [], [], $content);

        static::assertSame(Response::HTTP_FORBIDDEN, $kernelClient->getResponse()->getStatusCode());
    }

    /**
     * @return \Generator
     */
    public function ROLE_COMPANY_can_NOT_access_provider()
    {
        $userId = $this->getUserClientId();

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

        yield['/api/users',             'GET',      null                    ];
        yield['/api/users',             'POST',     json_encode($postUser)  ];
        yield['/api/users/'. $userId,   'PUT',      json_encode($putUser)   ];
        yield['/api/users/'. $userId,   'DELETE',   null                    ];
    }
}
