<?php

/**
 * Made By Raditya Firman Syaputra
 * Created on 23 June 2020
 */

function register($id, $email, $password, $otp, $resotp, $captcha)
{
    $curl = curl_init();
    $headers = array();
    $headers[] = "Host: pointblank.id";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Content-Length: 667";
    $headers[] = "Cache-Control: max-age=0";
    $headers[] = "Upgrade-Insecure-Requests: 1";
    $headers[] = "Origin: https://pointblank.id";
    $headers[] = "Content-Type: application/x-www-form-urlencoded";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36";
    $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
    $headers[] = "Sec-Fetch-Site: same-origin";
    $headers[] = "Sec-Fetch-Mode: navigate";
    $headers[] = "Sec-Fetch-User: ?1";
    $headers[] = "Sec-Fetch-Dest: document";
    $headers[] = "Referer: https://pointblank.id/member/signup";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9,id;q=0.8";
    $headers[] = "Cookie: SESSION=NWIxZGNlZWUtZDY4MS00ZjIyLTg5MTItODNmMGM4MTM1YmMx";

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://pointblank.id/member/process",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => "_viewstate=$resotp&userid=$id&password=" . urlencode($password) . "&repassword=" . urlencode($password) . "&email=" . urlencode($email) . "&code=$otp&g-recaptcha-response=$captcha",
        CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);
    return $response;
}

function check($url, $cookie, $response = false)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $headers = array();
    $headers[] = 'Host: pointblank.id';
    $headers[] = 'Connection: keep-alive';
    $headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
    $headers[] = 'X-Requested-With: XMLHttpRequest';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36';
    $headers[] = 'Content-Type: application/x-www-form-urlencoded;charset=UTF-8';
    $headers[] = 'Sec-Fetch-Site: same-origin';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Referer: https://pointblank.id/member/signup';
    $headers[] = 'Accept-Encoding: gzip, deflate, br';
    $headers[] = 'Accept-Language: en-US,en;q=0.9,id;q=0.8';
    $headers[] = $cookie;

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    $result = json_decode($result, true);
    if ($result['resultCode'] == "0") {
        return ($response) ? $result['resultData'] : true;
    } else {
        return false;
    }
}

function getCookies()
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_URL, 'https://pointblank.id/member/signup');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $headers = array();
    $headers[] = "Host: pointblank.id";
    $headers[] = "Connection: keep-alive";
    $headers[] = "Cache-Control: max-age=0";
    $headers[] = "Upgrade-Insecure-Requests: 1";
    $headers[] = "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36";
    $headers[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
    $headers[] = "Sec-Fetch-Site: same-origin";
    $headers[] = "Sec-Fetch-Mode: navigate";
    $headers[] = "Sec-Fetch-User: ?1";
    $headers[] = "Sec-Fetch-Dest: document";
    $headers[] = "Referer: https://pointblank.id/login/form";
    $headers[] = "Accept-Encoding: gzip, deflate, br";
    $headers[] = "Accept-Language: en-US,en;q=0.9,id;q=0.8";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    preg_match_all('/^Set-Cookie:\s*([^;\r\n]*)/mi', $result, $cookie);
    return $cookie[1][0];
}

function checkUsername($username, $cookie)
{
    return check("https://pointblank.id/member/IdCheck?id=" . $username . "", $cookie);
}

function checkEmail($email, $cookie)
{
    return check("https://pointblank.id/member/email/check?email=" . $email . "", $cookie);
}

function verifyOtp($email, $otp, $cookie)
{
    return check("https://pointblank.id/member/email/otp/process?email=" . $email . "&code=" . $otp . "", $cookie, true);
}
