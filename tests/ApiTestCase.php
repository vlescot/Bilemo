<?php
declare(strict_types=1);

namespace App\Tests;

use App\Domain\Entity\Company;
use App\Domain\Entity\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
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
     */
    protected function createUser(string $username, string $password)
    {
        $em = self::$kernel->getContainer()->get('doctrine.orm.default_entity_manager');

        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->loadUserByUsername($username);

        if (!$user) {
            $passwordEncoder = self::$kernel->getContainer()->get('security.password_encoder');

            $user = new User();
            $password = $passwordEncoder->encodePassword($user, $password);

            $user->registration(
                ['ROLE_USER'],
                $username,
                $password,
                $username . '@mail.com'
            );

            $em->persist($user);
            $em->flush();
        }
    }

    /**
     * @param string $username
     * @param string $password
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedUser(string $username, string $password)
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

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token'])); // $data[1] = string's token

        return $client;
    }

    protected function createAuthenticatedCompany()
    {
        $content  = json_encode([
            'username' => 'Bilemo',
            'password' => 'Bilemo'
        ]);


        $client = static::createClient();
        $client->request(
            'POST',
            'company/token',
            [],
            [],
            [],
            $content
        );


        $data = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token'])); // $data[1] = string's token

        return $client;
    }
}
