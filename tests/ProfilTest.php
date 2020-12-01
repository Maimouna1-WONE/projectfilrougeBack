<?php


namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfilTest extends WebTestCase
{

    public function createAuthenticatedClient(string $login, string  $password)
    {
        $client = static::createClient();
        $client->request(
            'POST', '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"login":"admin3","password":"pass_1234"}'
        );
        $data = \json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', \sprintf('Bearer %s', $data['token']));
        $client->setServerParameter('CONTENT_TYPE', 'application/json');
        return $client;
    }

    /*public function testGetProfil()
    {
        $client = $this->createAuthenticatedClient("admin3","pass_1234");
        $client->request("GET","/api/admin/profils?archive=false");
        $this->assertResponseStatusCodeSame(200, "Liste des profils");
    }*/
    public function testAddProfil()
    {
        $client = $this->createAuthenticatedClient("admin3","pass_1234");
        $data["libelle"]="TEST";
        $data["archive"]=false;
        //dd($client);
        $client->request("POST","/api/admin/profils", $data);
        $this->assertResponseStatusCodeSame(201,"ok");
    }
}