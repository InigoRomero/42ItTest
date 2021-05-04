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
        $client = new Client([
            'base_uri' => 'https://api.intra.42.fr',
            'timeout'  => 50000000.0,
        ]);
        $response = $client->request('POST', env("ACCESS_TOKEN_URI", ""), [
            'headers'  => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'grant_type' => 'authorization_code',
                'client_id' => env("CLIENT_ID", "") ,
                "scope" => "public" ,
                'client_secret' => env("CLIENT_SECRET", ""), 
                'code' => $code,
                'redirect_uri' => env("REDIRECT_URI", "")
            ])
        ]);
        $token = $response->getBody();
        $token = json_decode($token, true);
        $response = $client->request('GET', "https://api.intra.42.fr/v2/me", [
            'headers'  => [
                'Authorization' => "Bearer " . $token["access_token"]
            ]
        ]);

        return view('callback', ['me' => json_decode($response->getBody()->getContents(), true)]);
    }

}