<?php

declare(strict_types=1);

namespace App\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

define('SECRET_KEY', "casjiwAhncoelc123583uckasdcl");

class JWTUtils
{

    public static function generateJwtToken($data)
    {
        $payload = array(
            'iss' => 'ahnaf',
            'iat' => time(),
            'exp' => strtotime("+1 hour"),
            'data' => array(
                'user_id' => $data['id'],
                'user_name' => $data['user_name'],
                'role' => $data['role']
            )
        );

        $token = JWT::encode($payload, SECRET_KEY, 'HS256');

        return $token;
    }

    public static function validateUserLoggedIn()
    {
        $jwt = $_COOKIE['token'] ?? null;

        if (empty($jwt)) {
            echo "<script>alert('Error: User is Unauthorized');</script>";
            header('Location: /expense-tracker/public/login');
            exit;
        }

        $decode = JWT::decode($jwt, new Key(SECRET_KEY, 'HS256'));

        [$userId, $role] = [$decode->data->user_id, $decode->data->role];

        return [$userId, $role];
    }
}
