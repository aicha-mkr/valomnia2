<?php
namespace App\Models;
class Utils
{
   public static function createSlug($string) {
       // Convert the string to lowercase
       $string = strtolower($string);

       // Replace non-alphanumeric characters with dashes
       $string = preg_replace('/[^a-z0-9]+/', '-', $string);

       // Remove leading and trailing dashes
       $string = trim($string, '-');

       // Remove consecutive dashes
       $string = preg_replace('/-+/', '-', $string);

       return $string;
   }
}