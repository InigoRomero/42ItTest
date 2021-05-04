<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use League\OAuth2\Client\Token\AccessToken;
use Mehdibo\OAuth2\Client\Provider\FortyTwo;
use Mehdibo\OAuth2\Client\Provider\ResourceOwner;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2;
use Auth;
use GuzzleHttp\Client;


class callbackController extends Controller 
{

    public function callback(Request $request)
    {
        $code = $request->code;


      /*  $provider = new GenericProvider([
            'clientId'                => env("CLIENT_ID", "somedefaultvalue") ,   // The client ID assigned to you by the provider
            'clientSecret'            => env("CLIENT_SECRET", "somedefaultvalue") ,    // The client password assigned to you by the provider
            'redirectUri'             => env("REDIRECT_URI", "somedefaultvalue") ,
            'urlAuthorize'            => env("AUTHORIZATION_URI", "somedefaultvalue") ,
            'urlAccessToken'          => env("ACCESS_TOKEN_URI", "somedefaultvalue") ,
            'urlResourceOwnerDetails' => 'https://api.intra.42.fr/v2/me'
        ]);
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);*/
        $client = new Client([
            'base_uri' => 'https://api.intra.42.fr',
            'timeout'  => 50000000.0,
        ]);
        $response = $client->request('POST', env("ACCESS_TOKEN_URI", ""), [

            'headers'  => [
                'Content-Type' => 'multipart/form-data',
                'Authorization:' => "Bearer " . $code
            ],
            'form_params' => [
                
                'grant_type' => 'authorization_code',
                'client_id' => env("CLIENT_ID", "") ,
                'client_secret' => env("CLIENT_SECRET", ""), 
                'code' => $code,
                'redirect_uri' => env("REDIRECT_URI", "")
            ]
        ]);
        $token = $response->getBody();
        $token = json_decode($token, true);
        echo "Bearer " .$token["access_token"];
        $response = $client->request('GET', "https://api.intra.42.fr/v2/users", [
            'headers'  => [
                'Authorization' => "Bearer " . $token["access_token"]
            ]
        ]);

        return view('callback', ['token' => $response->getBody()]);
    }

}

/*            'multipart' => [
                [
                    'name'     => 'grant_type',
                    'contents' => 'authorization_code'
                ],
                [
                    'name'     => 'client_id',
                    'contents' => env("CLIENT_ID", "")
                ],                
                [
                    'name'     => 'client_secret',
                    'contents' => env("CLIENT_SECRET", "")
                ],
                [
                    'name'     => 'code',
                    'contents' => $code
                ],
                [
                    'name'     => 'redirect_uri',
                    'contents' => env("REDIRECT_URI", "")
                ],*/