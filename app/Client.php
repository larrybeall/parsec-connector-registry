<?php
/**
 * Created by PhpStorm.
 * User: Larry
 * Date: 6/6/17
 * Time: 9:54 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['client_key'];

    public static function exists($client_key)
    {
        $client = self::where('client_key', $client_key)->first();
        if(!$client)
        {
            return false;
        }

        return true;
    }
}