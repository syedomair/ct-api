<?php
namespace SyedOmair\Bundle\CoreBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;


class CategoryControllerTest extends WebTestCase
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

// syedomair_app_category_postcategory  POST   ANY    ANY  /app/category/{catalog_id}        

        $route =  $this->getUrl('syedomair_app_category_postcategory', array('catalog_id' => 1));
        $testDisplayName = 'Test Category Name';
        $requestPostArray = array('name'=> $testDisplayName);
        $requestPostArray = json_encode($requestPostArray);
        $this->client->request('POST', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'),$requestPostArray);
 
        $response = $this->client->getResponse();
        //var_dump($response->getContent());
        $this->assertEquals( $response->getStatusCode(), 200);

// syedomair_app_category_getcategory   GET    ANY    ANY  /app/category/{category_id}
        $route =  $this->getUrl('syedomair_app_category_getcategory', array('category_id' => 1));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        //var_dump($response->getContent());
        $categoryName = json_decode($response->getContent(), true)['data']['category']['name']; 
        $this->assertEquals($testDisplayName , $categoryName );

// syedomair_app_category_getcategories GET    ANY    ANY  /app/categories/{catalog_id}
        $route =  $this->getUrl('syedomair_app_category_getcategories', array('catalog_id' => 1, 'page'=>0));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        var_dump($response->getContent());
        $this->assertEquals( $response->getStatusCode(), 200);

    }
}
