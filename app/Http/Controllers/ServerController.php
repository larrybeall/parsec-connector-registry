<?php
/**
 * Created by PhpStorm.
 * User: Larry
 * Date: 6/7/17
 * Time: 10:06 PM
 */

namespace App\Http\Controllers;


use App\Client;
use App\Server;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function register(Request $request)
    {
        $client_key = $request->json->get('client_key');
        $server_identity = $request->json->get('identity');

        if($this->isHashValid($client_key) === false)
        {
            $this->invalidKeyLengthResponse(['message' => 'Invalid client key length']);
        }

        if($this->isHashValid($server_identity) === false)
        {
            $this->invalidKeyLengthResponse(['message' => 'Invalid identity key length']);
        }

        if(Client::exists($client_key) === false)
        {
            $this->itemNotFoundResponse(['message'=> 'Client key not found']);
        }

        if(Server::exists($client_key, $server_identity))
        {
            $this->alreadyExistsResponse(['message' => 'Server entry already exists']);
        }

        $locator_packet  = $request->json->get('locator_packet');

        $time = new Carbon();

        $server = Server::create([
            'client_key' => $client_key,
            'server_identity' => $server_identity,
            'heartbeat' => $time,
            'locator_packet' => $locator_packet,
            'locator_packet_updated' => $time,
            'created_at' => $time
        ]);

        return $this->createdResponse(['message' => 'Server registered successfully', 'server' => $server]);
    }

    public function beat(Request $request)
    {
        $client_key = $request->json->get('client_key');
        $server_identity = $request->json->get('identity');
        $locator_packet  = $request->json->get('locator_packet');
        $old_identity = $request->json->get('old_identity');

        $time = new Carbon();

        $toUpdate = ['heartbeat' => $time];

        if($locator_packet)
        {
            $toUpdate['locator_packet'] = $locator_packet;
            $toUpdate['locator_packet_updated'] = $time;
        }

        if($old_identity)
        {
            $toUpdate['server_identity'] = $server_identity;
        }

        $server = Server::where('client_key', $client_key)
            ->where('server_identity', ($old_identity) ? $old_identity : $server_identity)
            ->first();

        if(!$server)
        {
            return $this->itemNotFoundResponse(['message' => 'Server not found']);
        }

        $result = $server->update($toUpdate);

        if($result)
        {
            return $this->contentSuccessResponse(['message' => 'Server updated']);
        }

        return $this->itemUpdateFailedResponse(['message' => 'Failed to update server']);
    }

    public function getList(Request $request)
    {
        $client_key = $request->json->get('client_key');
        $last_request = $request->json->get('last_request');
        $force_packet = $request->json->get('force_packet');
        $last_request_time = ($last_request) ? new Carbon($last_request) : Carbon::now();

        if(Client::exists($client_key) === false)
        {
            return $this->itemNotFoundResponse(['message' => 'Client not found']);
        }

        $servers = Server::where('client_key', $client_key)->get();

        $response = ['request_time' => Carbon::now(), 'servers' => []];

        foreach ($servers as $server) {
            $last_heartbeat = new Carbon($server->heartbeat);
            $packet_time = new Carbon($server->locator_packet_updated);

            $online_status = $this->getOnlineStatus($last_heartbeat);
            $has_new_packet = $force_packet || $this->hasNewPacket($packet_time, $last_request_time);

            $server_info = [
                'identity' => $server->server_identity,
                'status' => $online_status,
                'packet' => ($has_new_packet) ? $server->locator_packet : null
            ];

            $response['servers'][] = $server_info;
        }

        return $this->contentSuccessResponse($response);
    }

    public function exists(Request $request)
    {
        $client_key = $request->json->get('client_key');
        $server_identity = $request->json->get('identity');

        $exists = Server::exists($client_key, $server_identity);

        return ($exists === true)
            ? $this->contentSuccessResponse(['message' => 'Server exists'])
            : $this->itemNotFoundResponse(['message' => 'Server does not exist']);
    }

    private function getOnlineStatus($heartbeat)
    {
        $time_since_last = Carbon::now()->diffInMinutes($heartbeat);

        if($time_since_last <= 5)
        {
            return 'online';
        }

        if($time_since_last <= 10)
        {
            return 'recent';
        }

        return 'offline';
    }

    private function hasNewPacket(Carbon $packet_time, Carbon $last_request)
    {
        return $packet_time->gte($last_request);
    }
}