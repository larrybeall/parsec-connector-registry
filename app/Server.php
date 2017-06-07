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
    protected $fillable = ['client_key', 'server_key'];
}