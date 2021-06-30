# php_dadata

## Установка

Подключите dadata.php к своему проекту, после регистрации в сервисе https://dadata.ru, в личных настройках будет указан API-ключ, его нужно скопировать и объявить константу DADATA_API_KEY:
````
define('DADATA_API_KEY', 'API-ключ');
````

Поиск данных компании по ИНН
````
$inn = 'ИНН компании';
$company = DaDataComponent::getParty($inn);
````

Поиск данных банка по БИК
````
$bik = 'БИК банка';
$bank = DaDataComponent::getBank($bik);
````

Поиск адресов по ФИАС
````
$strQueryAddress = 'строка поиска адреса';
$address = DaDataComponent::getFias($strQueryAddress);
````