<?php

namespace App\Foundations;

abstract class Controller
{
    /**
     * The API version for the controller.
     * @var string
     */
    private static $apiVersion = '1.0';

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        return $this->{$method}(...array_values($parameters));
    }

    /**
     * Send a standardized API response.
     *
     * @param mixed $data For the response payload
     * @param string $message Set the response message
     * @param int $statusCode Set the HTTP status code
     * @param array $meta Set the response meta information
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResponse($data = null, $message = 'Operation successful', $statusCode = 200, $meta = [])
    {
        $response = [
            'status' => $statusCode < 400 ? 'success' : 'error',
            'message' => $message,
            'data' => $data,
            'meta' => [
                'version' => self::$apiVersion,
                'timestamp' => date('c')
            ]
        ];

        if (!empty($meta)) {
            $response['meta'] = array_merge($response['meta'], $meta);
        }

        return response()->json($response, $statusCode);
    }
}
