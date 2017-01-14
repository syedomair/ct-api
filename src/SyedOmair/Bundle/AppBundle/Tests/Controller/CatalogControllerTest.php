<?php
namespace SyedOmair\Bundle\CoreBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;


class CatalogControllerTest extends WebTestCase
{

    public function setUp()
    {
        $this->auth = array(
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW'   => 'userpass',
        );
        $this->client = static::createClient(array(), $this->auth);
    }

    public function testGetAction()
    {

//syedomair_app_catalog_postcatalog POST   ANY    ANY  /app/catalog

        $route =  $this->getUrl('syedomair_app_catalog_postcatalog');
        $testDisplayName = 'Test Catalog Name';
        $requestPostArray = array('name'=> $testDisplayName);
        $requestPostArray = json_encode($requestPostArray);
        $this->client->request('POST', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'),$requestPostArray);
 
        $response = $this->client->getResponse();
        //var_dump($response->getContent());
        $this->assertEquals( $response->getStatusCode(), 200);

//syedomair_app_catalog_getcatalog  GET    ANY    ANY  /app/catalog/{catalog_id}

        $route =  $this->getUrl('syedomair_app_catalog_getcatalog', array('catalog_id' => 1));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        //var_dump($response->getContent());
        $catalogName = json_decode($response->getContent(), true)['data']['catalog']['name']; 
        $this->assertEquals($testDisplayName , $catalogName );

    }
}
