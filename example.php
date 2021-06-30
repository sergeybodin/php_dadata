<?php

require_once 'dadata.php';

// Поиск данных компании по ИНН
$company = DaDataComponent::getParty('7721581040');

// Поиск данных банка по БИК

$bank = DaDataComponent::getBank('044525225');

// Поиск адреса по ФИАС

$address = DaDataComponent::getFias('Москва Тверская 1');

print_r([
    'company' => $company,
    'bank' => $bank,
    'address' => $address
]);