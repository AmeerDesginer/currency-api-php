# currency-api-php
a class to make currency api more easy

# example:

```
<?php

include './currency.php';

$API_KEY = 'XXXXXXXX'; // you can get onw in your currency account
$API_SECRET = 'XXXXXXXXXXXXXXXXX'; // you can get onw in your currency account

$currency = new currency();

$currency->setMode('demo'); // demo | live // You can switch between demo or live mode
$currency->setAPIKey($API_KEY);
$currency->setAPISecret($API_SECRET);

                                      // path | You can see all pathes at https://apitradedoc.currency.com/swagger-ui.html
$accountResponse = $currency->request("account", [], "GET");

echo '<pre>';
print_r(json_decode($accountResponse));
echo '</pre>';
```
