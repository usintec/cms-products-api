<?php
namespace App\Http\Library;
use Illuminate\Http\JsonResponse;

trait ApiHelpers {
    protected function isAdmin($user): bool{
        if($user){
             return $user->tokenCan('admin');
        }
        return false;
    }
    protected function isUser($user): bool{
        if($user){
             return $user->tokenCan('user');
        }
        return false;
    }
    protected function onSuccess($message = '',$data = '',$code = 200){
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }
    protected function onError($message = '',$code = 200){
        return response()->json([
            'status' => $code,
            'message' => $message,
        ], $code);
    }

}