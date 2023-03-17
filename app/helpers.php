<?php

function getExchangeRate()
{
    $apiKey = '2IgR9K1rJbq7di669OeymgIm48PxVEh0';
    $url = "https://open.er-api.com/v6/latest/RUB?apikey={$apiKey}&symbols=USD";

    $response = file_get_contents($url);
    $data = json_decode($response, true);

    return $data['rates']['USD'];
}
