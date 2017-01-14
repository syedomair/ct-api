<?php
namespace SyedOmair\Bundle\AppBundle\Security;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use SyedOmair\Bundle\AppBundle\Security\CustomAuthToken;
use Symfony\Component\Security\Core\Util\StringUtils;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;

class CustomAuthProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;
    private $container;

    public function __construct(UserProviderInterface $userProvider, $cacheDir,  $container)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir     = $cacheDir;
        $this->container     = $container;
    }

    private static function base64url_encode($data) 
    { 
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
    } 

    public function authenticate(TokenInterface $token)
    {
            if($token->getUsername() == 'new_user_registration')
            {
                return $token;
            }
            else
            {
                $logger = $this->container->get('logger');
                $logger->info('OMAIR'); 

/*
               	$header = array( 
			"typ" => "JWT", 
			"alg" => "sha256"
		);


		$claims = array(
			"iss" => null,
			"aud" => null,
			"iat" => time(),
			"user" => 'khalid'//$user
		);
                $key = 'test';
		$segments = array();

		$segments[] = self::base64url_encode(json_encode($header));
		$segments[] = self::base64url_encode(json_encode($claims));
		
		$sigInput = implode(".",$segments);
		//$sig = hash_hmac('sha256', $sigInput, $key, true);
		$sig = hash_hmac('sha256', $sigInput, $key);
		$segments[] = self::base64url_encode($sig);

                $logger->info('TOKEN->'. implode(".",$segments)); 
*/
                $tokenId    = base64_encode(4);
                $issuedAt   = time();
                $notBefore  = $issuedAt;// + 10;             //Adding 10 seconds
                $expire     = $notBefore + 60;            // Adding 60 seconds
                $serverName = 'THE SERVER';//$config->get('serverName'); // Retrieve the server name from config file
    
                /*
                 * Create the token as an array
                 */
                $data = [
                    'iat'  => $issuedAt,         // Issued at: time when the token was generated
                    //'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                    //'iss'  => $serverName,       // Issuer
                    //'nbf'  => $notBefore,        // Not before
                    //'exp'  => $expire,           // Expire
                    'data' => [                  // Data related to the signer user
                        'userId'   => 100,//$rs['id'], // userid from the users table
                        'userName' => 'Syed Omair',//$username, // User name
                    ]
                ];


                $secretKey = base64_decode('khalid');
    
                /*
                 * Encode the array to a JWT string.
                 * Second parameter is the key to encode the token.
                 * 
                 * The output string can be validated at http://jwt.io/
                 */
                $jwt = JWT::encode(
                    $data,      //Data to be encoded in the JWT
                    $secretKey, // The signing key
                    'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
                );
        

                $logger->info('OMAIR TOKEN->'. $jwt);


                $token1 = JWT::decode($jwt, $secretKey, array('HS512'));

                $logger->info('OMAIR DECODE TOKEN->'. json_encode($token1));












                $this->user = $this->userProvider->loadUserByUsername(array($token->getUsername()));

                if($this->user ) 
                {
                    $plainUserPassword = base64_decode($token->encryptedPass);
                    if($this->_hash_equals(crypt($plainUserPassword, $this->user->getSalt()), $this->user->getPassword())) 
                    {
                        $authenticatedToken = new CustomAuthToken($this->user->getRoles());
                        $authenticatedToken->setUser($this->user);
                        return $authenticatedToken;
                    }
                }

            }
        throw new AuthenticationException('Authentication failed.');
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof CustomAuthToken;
    }

    private function _hash_equals($str1, $str2) 
    {
        if(strlen($str1) != strlen($str2)) 
        {
            return false;
        } 
        else 
        {
          $res = $str1 ^ $str2;
          $ret = 0;
          for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
          return !$ret;
        }
    }

}
