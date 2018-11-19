<?php
declare(strict_types=1);

namespace App\Tests;

use App\Domain\Entity\Phone;
use App\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    protected function setUp()
    {
        parent::setUp();

        self::bootKernel();
    }


    /**
     * @param array $expectedContent
     * @param array $responseContent
     */
    protected function assertResponseContent(array $expectedContent, array $responseContent)
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


    /**
     * @param string $username
     * @param string $password
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */

    protected function getAuthenticatedUserClient(string $username, string $password)
    {
        $body  = json_encode([
            'username' => $username,
            'password' => $password
        ]);

        $client = static::createClient();
        $client->request('POST', '/api/token/user', [], [], [], $body);

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


    protected function getUserId($username = null)
    {
        if (!$username) {
            $username = 'User0';
        }

        $repository = $this->getRepository(User::class);
        $user = $repository->findOneBy(['username' => $username]);
        return $user->getId();
    }

    protected function getEntityBy(string $entity, array $params)
    {
        $repository = $this->getRepository($entity);
        return $repository->findOneBy($params);
    }
}
