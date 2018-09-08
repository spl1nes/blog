# PHP - Exchange Rate Calculator

Calculating the value of foreign currencies can be very important for certain applications and businesses. While it's possible to use many of the pre-existing live APIs you can also use some public sources which also provide very reliable data. In the following you'll find the code used to convert between some of the most common currencies based on the exchange rates provided by the European Central Bank (ECB).

## Requirements

* php 7.0
* In order for the code to work you need to [allow_url_fopen](http://php.net/manual/en/filesystem.configuration.php).
* Alternatively you could replace `file_get_contents` with a curl implementation, which however requires curl to be installed and enabled.

## Implementation

First we have to download the data from the ECB.

```php
function getEcbEuroRates() : array
{
    $ecbCurrencies = [];
    $response      = \file_get_contents('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
    $xml           = new \SimpleXMLElement($response);

    if (!isset($xml->Cube)) {
        throw new \Exception('Invalid xml path');
    }

    $node          = $xml->Cube->Cube->Cube;
    $ecbCurrencies = [];

    foreach ($node as $key => $value) {
        $ecbCurrencies[\strtoupper((string) $value->attributes()['currency'])] = (float) $value->attributes()['rate'];
    }
}
```

Caching this (daily) data provided by the ECB for better performance instead of downloading it every time is left as exercise for the reader.

> Note: Instead of using XML parsing a regex match could have been used as well. 

Now since the difficult part is done all we have to do is convert one currency value to a different currency value by using the downloaded exchange rates. It's important to note that These rates are always based on EUR. This means if we have a base currency different than EUR we first need to convert it to EUR.

```php
function convertCurrency(float $value, string $from, string $to) : float
{
    $currencies = getEcbEuroRates();
    $from       = \strtoupper($from);
    $to         = \strtoupper($to);

    if (!isset($currencies[$from]) || !isset($currencies[$to])) {
        throw new \InvalidArgumentException('Currency doesn\'t exists');
    }

    if ($from !== 'EUR') {
        $value /= $currencies[$from];
    }

    return $to === 'EUR' ? $value : $value * $currencies[$to];
}
```

Instead of throwing an exception you might want to return `0.0` as value if the conversion failed. This may however cause significant problems if you forget to check for `0.0` as return value where an exception is much harder to miss.

### Curl Alternative

Instead of using `file_get_contents()` the following function could have been used to retrieve the data without enabling `allow_url_fopen`

```php
function getContent(string $url) : string
{
    $ch = \curl_init();
    \curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    \curl_setopt($ch, CURLOPT_URL, $url);

    $data = \curl_exec($ch);
    \curl_close($ch);

    return $data;
}
```
