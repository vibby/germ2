<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Zenstruck\Foundry\Test\ResetDatabase;

class LoginTest extends ApiTestCase
{
    use ResetDatabase;

    public function testLogin(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword(
            $container->get('security.user_password_hasher')->hashPassword($user, '$3CR3T')
        );

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', '/auth', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'test@example.com',
                'password' => '$3CR3T',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        // test not authorized
        $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/users', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
    }
}