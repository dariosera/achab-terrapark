<?php

$str = $d["input"];

// Normalize accented characters
$str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);

// Convert to lowercase
$str = strtolower($str);

// Remove all characters that are not alphanumeric or spaces
$str = preg_replace('/[^a-z0-9\s]/', '', $str);

// Replace spaces with dashes
$str = preg_replace('/\s+/', '-', $str);

return ["output" => $str];