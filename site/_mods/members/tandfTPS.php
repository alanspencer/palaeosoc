<?php
if (!$MEMBERVERIFIED) {
    header('Location: https://www.palaeosoc.org');
    exit();
}

/*
 * 'ru' stands for return URL and you must replace it with the URL of the page for the journal
 * where you would like your members to arrive.
 */
$ru = 'https://www.tandfonline.com/toc/tmps20/current';
$domain = 'www.palaeosoc.org';
$host = 'www.tandfonline.com';
$protocol = 'https://';

$ticketurl = $protocol . $host . '/tps/requestticket?ru=' . urlencode($ru) . '&debug=true&domain=' . urlencode($domain);

/*
 * Server configurations vary. Use fopen or curl to fetch the URL.
 */
$error_occured = false;
if (ini_get('allow_url_fopen') == true) {
    if (!($fp = fopen($ticketurl, 'r'))) {
        $error_occured = true;
    } else {
        $redirecturl = fread($fp, 1000000);
    }
    fclose($fp);
} elseif (is_callable('curl_init')) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $ticketurl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    $redirecturl = curl_exec($curl);
    curl_close($curl);
}

if ($error_occured || empty($redirecturl)) {
    echo 'The Trusted Proxy Server failed to establish connection';
} else {
    header('Location: ' . $redirecturl);
    exit();
}