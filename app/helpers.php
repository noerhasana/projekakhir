<?php

function toastSuccess($message)
{
    return [
        'type' => 'success',
        'title' => "Berhasil",
        'detail' => $message,
    ];
}

function toastInfo($message)
{
    return [
        'type' => 'info',
        'title' => "Informasi",
        'detail' => $message,
    ];
}

function toastWarning($message)
{
    return [
        'type' => 'warning',
        'title' => "Peringatan",
        'detail' => $message,
    ];
}

function toastFailed($message)
{
    return [
        'type' => 'error',
        'title' => "Gagal",
        'detail' => $message,
    ];
}

function responseJson($message, $errors, $code=200)
{
    return response()->json([
        'message' => $message,
        'errors' => $errors
    ], $code);
}