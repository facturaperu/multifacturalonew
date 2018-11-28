<?php

function to_json_error($message, $code)
{
    return to_json([
        'success' => false,
        'message' => $message
    ], $code);
}

function to_json($response, $code = 200)
{
    return response()
        ->json($response, $code);
}