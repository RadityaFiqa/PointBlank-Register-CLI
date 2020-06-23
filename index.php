<?php

require_once './lib/deathbycaptcha.php';
require_once './lib/function.php';

$usernameDBC = "";
$passwordDBC = "";

$client = new DeathByCaptcha_HttpClient($usernameDBC, $passwordDBC);

echo "Your balance is {$client->balance} US cents\n";
$client->is_verbose = true;

$data = array(

    'googlekey'       => '6LedY2IUAAAAAK-55KzamdPZeFh6fbZnDcRhzDLE',
    'pageurl' => 'https://pointblank.id/member/signup'
);
$json = json_encode($data);
$extra = [
    'type' => 4,
    'token_params' => $json,
];


$cookie = getCookies();
if ($cookie) {

    echo ("Masukan ID :");
    $id = trim(fgets(STDIN));

    $cekuname = checkUsername($id, $cookie);
    if ($cekuname) {
        echo "Registering Using Username $id\n";

        echo ("Masukan Email :");
        $email = trim(fgets(STDIN));

        $cekmail = checkEmail($email, $cookie);
        if ($cekmail) {
            echo "Registering Using Email $email\n";

            echo "Please kindly wait , Getting Captcha Response...\n";
            if ($captcha = $client->decode(null, $extra)) {
                sleep(DeathByCaptcha_Client::DEFAULT_TIMEOUT);

                if ($text = $client->get_text($captcha['captcha'])) {

                    echo "\n\nSuccessfully Getting Captcha Response\n";
                    echo "Masukan OTP :";
                    $otp = trim(fgets(STDIN));

                    $cekOtp = verifyOtp($email, $otp, $cookie);
                    if ($cekOtp) {
                        $regist = register($id, $email, "Raditya@123", $otp, $cekOtp, $text);
                        if ($regist) {
                            echo '\nSukses Created';
                        } else {
                            echo '\nGagal';
                        }
                    } else {
                        echo 'OTP Salah';
                    }
                }
            }
        } else {
            echo "Error Email Not Valid\n";
        }
    } else {
        echo "Error Username Not Valid";
    }
}
