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

        if(strlen($client_key) !== 128)
        {
            return $this->invalidKeyLengthResponse(['message' => 'Invalid client key length']);
        }

        $client = Client::where('client_key', $client_key)->first();

        if($client)
        {
            return $this->alreadyExistsResponse(['message' => 'Client with this key already exists']);
        }

        $client = Client::create(['client_key' => $client_key]);

        return $this->createdResponse(['message' => 'Client registered successfully', 'client' => $client]);
    }

    public function exists(Request $request)
    {
        $client_key = $request->json()->get('client_ke');
        $client = Client::where('client_key', $client_key)->first();

        return ($client)
            ? $this->contentSuccessResponse(['message' => 'Client exists'])
            : $this->itemNotFoundResponse(['message' => 'Client does not exist']);
    }
}