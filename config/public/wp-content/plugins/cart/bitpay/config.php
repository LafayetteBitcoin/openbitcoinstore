<?php

// ecwid settings
$storeURL = 'https://spw-store.herokuapp.com/';  // example: 'http://www.example.com/ecwid/index.html'
$storeId = '2136144'; // found in your ecwid control panel, bottom-right

// bitpay settings
// url of bitpay folder on your server.  example: 'http://www.example.com/ecwid/bitpay/
$bitpayURL = 'https://spw-store.herokuapp.com/wp-content/plugins/cart/bitpay/'; 
// apiKey: create this at bitpay.com in your account settings and paste it here
$apiKey = '7bOSaT7QlxZ4LK3CWNPb6YKhJ9785fpMFXavuydZoVE';  // ex 'DNboT9fVNpW7usAuDNboT9fVNpW7usAu'
// speed: Warning: on medium/low, customers will not see an order confirmation page.  
$speed = 'high'; // can be 'high', 'medium' or 'low'.  See bitpay API doc for more details.

//payment method settings
$login = 'ci2KMqD4wc9kQY3w'; // see README
$hashValue = '32dG4trRspBpVo3V'; // see README

// add trailing slash to url
$bitpayURL = preg_replace('#([^\/])$#', '\1/', $bitpayURL);

?>