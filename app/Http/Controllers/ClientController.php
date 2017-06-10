<?php
/**
 * Created by PhpStorm.
 * User: lbeall
 * Date: 6/7/17
 * Time: 12:19 AM
 */

namespace App\Http\Controllers;


use App\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function register(Request $request)
    {
        $client_key = $request->json()->get('client_key');

        if($this->isHashValid($client_key) === false)
        {
            return $this->invalidKeyLengthResponse(['message' => 'Invalid client key length']);
        }

        if(Client::exists($client_key))
        {
            return $this->alreadyExistsResponse(['message' => 'Client with this key already exists']);
        }

        $client = Client::create(['client_key' => $client_key]);

        return $this->createdResponse(['message' => 'Client registered successfully', 'client' => $client]);
    }

    public function exists(Request $request)
    {
        $client_key = $request->json()->get('client_key');
        $client = Client::exists($client_key);

        return ($client === true)
            ? $this->contentSuccessResponse(['message' => 'Client exists'])
            : $this->itemNotFoundResponse(['message' => 'Client does not exist']);
    }
}