<?php
declare(strict_types=1);

namespace App\Tests;

use App\Domain\Entity\Phone;
use App\Domain\Entity\Client;
use App\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiTestCase extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();

        self::bootKernel();
    }


    private function assertResponseContent(array $expectedContent, array $responseContent)
    {
        foreach ($expectedContent as $attributes => $type) {
            if (\is_array($type)) {
                static::assertArrayHasKey($attributes, $responseContent);

                if (\is_int(key($type))) {
                    $this->assertResponseContent($type[0], $responseContent[$attributes][0]);
                } else {
                    $this->assertResponseContent($type, $responseContent[$attributes]);
                }
            } else {
                static::assertArrayHasKey($attributes, $responseContent);
                static::assertInternalType($type, $responseContent[$attributes]);
            }
        }
    }


    protected function assertResponse(
        string $method,
        string $uri,
        int $expectedStatusCode,
        string $expectedContentType,
        array $expectedContent,
        bool $isClientUser = false
    ) {
        if ($isClientUser) {
            $kernelClient = $this->getAuthenticatedClient('Client2', 'password2');
        } else {
            $kernelClient = $this->getAuthenticatedCompanyClient();
        }

        $kernelClient->request($method, $uri);

        $responseContent = json_decode($kernelClient->getResponse()->getContent(), true);

        if ($kernelClient->getResponse()->getStatusCode() === 500 || $kernelClient->getResponse()->getStatusCode() === 404) {
            $this->showError($kernelClient->getResponse());
        }

        static::assertSame($expectedStatusCode, $kernelClient->getResponse()->getStatusCode());
        static::assertSame($expectedContentType, $kernelClient->getResponse()->headers->get('Content-Type'));
        $this->assertResponseContent($expectedContent, $responseContent);
    }


    protected function getAuthenticatedClient(string $username = 'Client1', string $password = 'password1')
    {
        $body  = json_encode([
            'username' => $username,
            'password' => $password
        ]);

        $client = static::createClient();
        $client->request('POST', '/api/token/client', [], [], [], $body);

        if ($client->getResponse()->getStatusCode() !== 200) {
            $this->showError($client->getResponse());
        }
        $data = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }


    protected function getAuthenticatedCompanyClient()
    {
        $content  = json_encode([
            'username' => 'Bilemo',
            'password' => 'Bilemo'
        ]);

        $client = static::createClient();
        $client->request('POST', '/api/token/company', [], [], [], $content);

        $data = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token'])); // $data[1] = string's token

        return $client;
    }


    private function getRepository($entityName)
    {
        self::bootKernel();
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        return $em->getRepository($entityName);
    }


    protected function getPhoneId(string $model = null)
    {
        if (!$model) {
            $model = 'G100';
        }

        $repository = $this->getRepository(Phone::class);
        $phone = $repository->findOneBy(['model' => $model]);
        return $phone->getId();
    }


    protected function getClientId($username = null)
    {
        if (!$username) {
            $username = 'Client0';
        }

        $repository = $this->getRepository(Client::class);
        $client = $repository->findOneBy(['username' => $username]);

        return $client->getId()->toString();
    }

    protected function getUserId(string $user = null)
    {
        if (!$user) {
            $user = 'User0';
        }

        $repository = $this->getRepository(User::class);
        $user = $repository->findOneBy(['name' => $user]);
        return $user->getId();
    }

    public function getUserClientId(string $usernameClient = null): string
    {
        $clientId = $this->getClientId($usernameClient);

        $repository = $this->getRepository(Client::class);
        $client = $repository->findOneById($clientId);

        return $client->getUsers()->toArray()[0]->getId()->toString();
    }

    protected function findBy(string $entity, array $params)
    {
        $repository = $this->getRepository($entity);
        return $repository->findOneBy($params);
    }

    protected function showError(Response $response)
    {
        dump($response->getStatusCode());
        dump(json_decode($response->getContent(), true));
    }
}
