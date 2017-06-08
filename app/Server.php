<?php
/**
 * Created by PhpStorm.
 * User: Larry
 * Date: 6/6/17
 * Time: 9:55 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'client_key',
        'server_identity',
        'heartbeat',
        'locator_packet',
        'locator_packet_updated',
        'created_at'
    ];

    public static function exists($client_key, $identity)
    {
        $server = self::where('client_key', $client_key)
            ->where('server_identity', $identity)
            ->first();

        if(!$server)
        {
            return false;
        }

        return true;
    }
}