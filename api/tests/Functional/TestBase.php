<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class TestBase extends WebTestCase
{
    use FixturesTrait;
    use RecreateDatabaseTrait;

    protected static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $yamid = null;
    protected static ?KernelBrowser $carlos = null;
    protected static ?KernelBrowser $jose = null;

    protected function setUp(): void
    {
        if (null === self::$client) {
            self::$client = static::createClient();
            self::$client->setServerParameters(
                [
                    'CONTENT_TYPE' => 'application/json',
                    'HTTP_ACCEPT' => 'application/ld+json',
                ]
            );
        }

        if (null === self::$yamid) {
            self::$yamid = clone self::$client;
            $this->createAuthenticatedUser(self::$yamid, 'yamid@api.com');
        }

        if (null === self::$carlos) {
            self::$carlos = clone self::$client;
            $this->createAuthenticatedUser(self::$carlos, 'carlos@api.com');
        }


        if (null === self::$jose) {
            self::$jose = clone self::$client;
            $this->createAuthenticatedUser(self::$jose, 'jose@api.com');
        }


    }

    private function createAuthenticatedUser(KernelBrowser &$client, string $email): void
    {
        $user = $this->getContainer()->get('App\Repository\UserRepository')->findOneByEmailOrFail($email);
        $token = $this
            ->getContainer()
            ->get('Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface')
            ->create($user);

        $client->setServerParameters(
            [
                'HTTP_Authorization' => \sprintf('Bearer %s', $token),
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/ld+json',
            ]
        );
    }

    protected function getResponseData(Response $response): array
    {
        return \json_decode($response->getContent(), true);
    }

    protected function initDbConnection(): Connection
    {
        return $this->getContainer()->get('doctrine')->getConnection();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException|Exception
     */
    protected function getYamidId()
    {
        return $this->initDbConnection()->fetchOne('SELECT id FROM user WHERE email = "yamid@api.com"');
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     */
    protected function getCarlosId()
    {
        return $this->initDbConnection()->fetchOne('SELECT id FROM user WHERE email = "carlos@api.com"');
    }
}