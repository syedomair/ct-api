<?php
namespace SyedOmair\Bundle\CoreBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;

class ProductControllerTest extends WebTestCase
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
// syedomair_app_product_postproduct    POST   ANY    ANY  /app/product/{category_id}        
        $route =  $this->getUrl('syedomair_app_product_postproduct', array('category_id' => 1));
        $testDisplayName = 'Test Product Name';
        $requestPostArray = array('name'=> $testDisplayName, 'sku'=>'ABC123', 'price'=>'12.30', 'short_desc'=>'desc');
        $requestPostArray = json_encode($requestPostArray);
        $this->client->request('POST', $route, array(), array(), array('CONTENT_TYPE' => 'application/json'),$requestPostArray);
 
        $response = $this->client->getResponse();
        //var_dump($response->getContent());
        $this->assertEquals( $response->getStatusCode(), 200);

// syedomair_app_product_getproduct     GET    ANY    ANY  /app/product/{product_id}
        $route =  $this->getUrl('syedomair_app_product_getproduct', array('product_id' => 1));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        //var_dump($response->getContent());
        $categoryName = json_decode($response->getContent(), true)['data']['product']['name']; 
        $this->assertEquals($testDisplayName , $categoryName );

        $route =  $this->getUrl('syedomair_app_product_getproducts', array('category_id' => 1 ));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        var_dump($response->getContent());
        $this->assertEquals( $response->getStatusCode(), 200);

    }
}
