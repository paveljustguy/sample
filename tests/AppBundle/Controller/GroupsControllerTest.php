<?php

namespace tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GroupsControllerTest extends WebTestCase
{
    public function testFetchListOfGroups()
    {
        $client = static::createClient();
        $client->request('GET', '/groups');

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));

        $groups = json_decode($response->getContent(), true);

        $this->assertNotEmpty($groups);

        foreach ($groups as $group) {
            $expectedFields = ['id', 'name'];

            foreach ($expectedFields as $field) {
                $this->assertArrayHasKey($field, $group);
            }
        }
    }

    public function testCreateNewGroup()
    {
        $group = ['name' => 'Awesome group'];

        $client = static::createClient();
        $client->request('POST', '/groups', [], [], [], json_encode($group));

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));

        $location = $response->headers->get('Location');
        $this->assertNotEmpty($location);
    }

    public function testCreateNewGroupValidationFail()
    {
        $group = ['name' => ''];

        $client = static::createClient();
        $client->request('POST', '/groups', [], [], [], json_encode($group));

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
    }
}
