<?php

class AUTHORIZATION
{
    public static function validateTimestamp($token)
    {
        $CI =& get_instance();
        $token = self::validateToken($token);
        if ($token != false && (now() - $token->timestamp < ($CI->config->item('token_timeout') * 60))) {
            return $token;
        }
        return false;
    }

    public static function validateToken($token)
    {
        $CI =& get_instance();
        return JWT::decode($token, $CI->config->item('jwt_key'));
    }

    public static function generateToken($data)
    {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_key'));
    }

    public static function verifyUser()
    {
        $CI =& get_instance();
        $headers = $CI->input->request_headers();

        $responseArr['valid'] = 0;
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $headers['Authorization'] = str_replace('Bearer ', '', $headers['Authorization']);
            $decodedToken = self::validateTimestamp($headers['Authorization']);
            if ($decodedToken != false) {
                $responseArr['valid'] = 1;
                $responseArr['userId'] = $decodedToken->id;
               
            }
        }

        return $responseArr;
        
    }

    public static function getRequest()
    {
        return (array) json_decode(file_get_contents("php://input"));
    }
}