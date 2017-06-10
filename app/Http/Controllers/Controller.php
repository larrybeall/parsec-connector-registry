<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function test()
    {
        return $this->contentSuccessResponse(['message' => 'it worked']);
    }

    protected function isHashValid($hash)
    {
        return strlen($hash) === 128;
    }

    protected function alreadyExistsResponse(array $content)
    {
        return $this->sendJsonResponse($content, 409, false);
    }

    protected function createdResponse(array $content)
    {
        return $this->sendJsonResponse($content, 201);
    }

    protected function invalidKeyLengthResponse(array $content)
    {
        return $this->sendJsonResponse($content, 400, false);
    }

    protected function contentSuccessResponse(array $content)
    {
        return $this->sendJsonResponse($content, 200);
    }

    protected function itemNotFoundResponse(array $content)
    {
        return $this->sendJsonResponse($content, 404, false);
    }

    protected function itemUpdateFailedResponse(array $content)
    {
        return $this->sendJsonResponse($content, 400, false);
    }

    private function sendJsonResponse(array $content, $code, $success = true)
    {
        $content['code'] = $code;
        $content['status'] = ($success === true) ? 'success' : 'error';
        return response()->json($content, $code);
    }
}
