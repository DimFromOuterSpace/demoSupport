<?php

namespace App\Utils;


class Sanitize
{
    /**
     * @param string $email
     * @return string
     */
    public static function sanitizeEmail(string $email)
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }



    public static function sanitizeText(string $text)
    {
        return trim(strtolower($text));
    }

}