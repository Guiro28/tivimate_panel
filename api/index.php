<?php
include(__DIR__ . '/../includes/functions.php');
session_start();  
$res = $db->select('dns', '*', '', '');

// Fetch the welcome message from the database
$mes = $db->select('welcome', '*', 'id = :id', '', [':id' => 1]);

// Initialize an array to hold the portals data
$rows = array();
foreach ($res as $index => $row) {
    $rows[] = array(
        "id" => $index + 1,
        "name" => $row['title'],
        "url" => $row['url']
    );
}

// Create the final JSON structure
$final = array(
    "portals" => $rows,
    "intro_url" => "",
    "message_one" => $mes[0]['message_one'],
    "message_two" => $mes[0]['message_two'],
    "message_three" => $mes[0]['message_three']
);


function getCustomValue($number) {    
    $values = array(      
        0 => "19fe",      
        1 => "164f",      
        2 => "25cb",      
        3 => "55ec",      
        4 => "945d",      
        5 => "888c",      
        6 => "02de",      
        7 => "199e",      
        8 => "423f",      
        9 => "978d"   
    );    
    $numberString = strval($number);      
    $result = '';     

    for ($i = 0; $i < strlen($numberString); $i++) {      
        $digit = intval($numberString[$i]);   
        if (isset($values[$digit])) {     
            $result .= $values[$digit];   
        }     
    }     

    return $result;   
}     

function encr($data, $key) {      
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, 'R5tghjg^999(@#Gg');   
    $encrypted_hex = bin2hex($encrypted);     
    return $encrypted_hex;    
}     

function generateRandomKeyfirst() {   
    if (!isset($_SESSION['random_key'])){    
    
        $_SESSION['random_key'] = '';     
        for ($i = 0; $i < 32; $i++) {     
            $_SESSION['random_key'] .= mt_rand(0, 9);     
        }     
    }     
    return $_SESSION['random_key'];   
}     


$enKey = generateRandomKeyfirst();    

header('Content-type: application/json; charset=UTF-8');
$sulanga = json_encode($final, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);


$output_json = '{"dns_backup":"'.Encryption::runfake().'","portals":' . getCustomValue($enKey).encr($sulanga,$enKey) . ',"url_backup":"'.Encryption::runfake().'"}';    
header('Content-type: application/json; charset=UTF-8');      

$json = Encryption::run($output_json);     
echo $json; 
session_destroy(); 
?>
