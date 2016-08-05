<?php

namespace tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UsersControllerTest extends WebTestCase
{
    public function testFetchListOfUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/users');

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));

        $users = json_decode($response->getContent(), true);

        $this->assertNotEmpty($users);

        foreach ($users as $user) {
            $this->assertUserFields($user);
        }
    }

    public function testFetchUser()
    {
        $client = static::createClient();
        $client->request('GET', '/users/1');

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));

        $user = json_decode($response->getContent(), true);

        $this->assertNotEmpty($user);
        $this->assertUserFields($user);
    }

    public function testCreateUser()
    {
        $user = [
            'email' => 'email31337@sampleapp.dev',
            'firstName' => 'Ivan',
            'lastName' => 'Ivanov',
            'active' => true,
        ];

        $client = static::createClient();
        $client->request('POST', '/users', [], [], [], json_encode($user));

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));

        $location = $response->headers->get('Location');
        $this->assertNotEmpty($location);
    }

    public function testCreateUserValidationFail()
    {
        $user = [];

        $client = static::createClient();
        $client->request('POST', '/users', [], [], [], json_encode($user));

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));

        $message = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $message);
    }

    public function testModifyUser()
    {
        $user = ['firstName' => 'John', 'lastName' => 'Doe', 'group' => 1];

        $client = static::createClient();
        $client->request('POST', '/users/1', [], [], [], json_encode($user));

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
    }

    private function assertUserFields(array $user)
    {
        static $fields = ['id', 'email', 'firstName', 'lastName', 'active', 'createdAt'];

        foreach ($fields as $field) {
            $this->arrayHasKey($field, $user);
        }
    }
}
