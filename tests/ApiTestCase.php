<?php
declare(strict_types=1);

namespace App\Tests;

use App\Domain\Entity\Phone;
use App\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{

    public function setUp()
    {
        parent::setUp();

        self::bootKernel();
    }


    /**
     * @param string $username
     * @param string $password
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */

    protected function createAuthenticatedUser(string $username, string $password)
    {
        $body  = json_encode([
            'username' => $username,
            'password' => $password
        ]);

        $client = static::createClient();
        $client->request('POST','/api/token/user', [], [], [], $body);

        $data = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }


    protected function createAuthenticatedCompany()
    {
        $content  = json_encode([
            'username' => 'Bilemo',
            'password' => 'Bilemo'
        ]);

        $client = static::createClient();
        $client->request('POST','/api/token/company',[],[],[],$content);

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


    public function getPhoneId()
    {
        $repository = $this->getRepository(Phone::class);
        $phone = $repository->findOneBy(['model' => 'S100']);
        return $phone->getId();
    }


    public function getUserId($username = null)
    {
        if (!$username) {
            $username = 'User0';
        }

        $repository = $this->getRepository(User::class);
        $user = $repository->findOneBy(['username' => $username]);
        return $user->getId();
    }
}
