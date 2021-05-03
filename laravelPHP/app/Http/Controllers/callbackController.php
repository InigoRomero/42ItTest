<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use League\OAuth2\Client\Provider\GenericProvider;

class callbackController extends Controller 
{

    public function callback(Request $request)
    {
        $code = $request->code;
        $provider = new League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => env("CLIENT_ID", "somedefaultvalue") ,   // The client ID assigned to you by the provider
            'clientSecret'            => env("CLIENT_SECRET", "somedefaultvalue") ,    // The client password assigned to you by the provider
            'redirectUri'             => env("REDIRECT_URI", "somedefaultvalue") ,
            'urlAuthorize'            => env("AUTHORIZATION_URI", "somedefaultvalue") ,
            'urlAccessToken'          => env("ACCESS_TOKEN_URI", "somedefaultvalue")
           // 'urlResourceOwnerDetails' => 'https://service.example.com/resource'
        ]);
        
            try {
        
                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $code
                ]);
            } catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            
                // Failed to get the access token or user details.
                exit($e->getMessage());
        
            }
              /*  $resourceOwner = $provider->getResourceOwner($accessToken);
        
                var_export($resourceOwner->toArray());
        
                // The provider provides a way to get an authenticated API request for
                // the service, using the access token; it returns an object conforming
                // to Psr\Http\Message\RequestInterface.
                $request = $provider->getAuthenticatedRequest(
                    'GET',
                    'https://service.example.com/resource',
                    $accessToken
                );
        
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        
                // Failed to get the access token or user details.
                exit($e->getMessage());
        
            }*/
        
            return view('callback', ['token' => $accessToken]);
        }

}