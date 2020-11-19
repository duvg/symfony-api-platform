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
     * @throws DBALException|Exception
     */
    protected function getYamidGroupId()
    {
        return $this->initDbConnection()->fetchOne('SELECT id FROM user_group WHERE name = "Yamid Group"');
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException|Exception
     */
    protected function getYamidExpenseCategoryId()
    {
        return $this->initDbConnection()->fetchOne('SELECT id FROM category WHERE name = "Yamid Expense Category"');
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException|Exception
     */
    protected function getYamidGroupExpenseCategoryId()
    {
        return $this->initDbConnection()->fetchOne('SELECT id FROM category WHERE name = "Yamid Group Expense Category"');
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException|Exception
     */
    protected function getYamidMovementId()
    {
        return $this->initDbConnection()->fetchOne('SELECT id FROM movement WHERE amount = 200');
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException|Exception
     */
    protected function getYamidGroupMovementId()
    {
        return  $this->initDbConnection()->fetchOne('SELECT id FROM movement WHERE amount = 300');
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

    /**
     * @return false|mixed
     *
     * @throws DBALException|Exception
     */
    protected function getCarlosGroupId()
    {
        return $this->initDbConnection()->fetchOne('SELECT id FROM user_group WHERE name = "Carlos Group"');
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException|Exception
     */
    protected function getCarlosExpenseCategoryId()
    {
        return $this->initDbConnection()->fetchOne('SELECT id FROM category WHERE name = "Carlos Expense Category"');
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException|Exception
     */
    protected function getCarlosGroupExpenseCategoryId()
    {
        return $this->initDbConnection()->fetchOne('SELECT id FROM category WHERE name = "Carlos Group Expense Category"');
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException|Exception
     */
    protected function getCarlosMovementId()
    {
        return $this->initDbConnection()->fetchOne('SELECT id FROM movement WHERE amount = 1000');
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException|Exception
     */
    protected function getCarlosGroupMovementId()
    {
        return  $this->initDbConnection()->fetchOne('SELECT id FROM movement WHERE amount = 2000');
    }
}