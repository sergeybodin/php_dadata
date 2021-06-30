<?php
/**
 * ключ авторизации, после регистрации в сервисе https://dadata.ru, будет указан в личных настройках
 */
if(!defined('DADATA_API_KEY')) define('DADATA_API_KEY', '');

class DaDataComponent
{
    /**
     * @var string - ресурс
     */
    private $url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/';

    /**
     * Поиск данных компании по ИНН
     * @param $inn - ИНН компании
     * @return false|mixed
     */
    public static function getParty($inn)
    {
        $component = new self();
        return $component->getData($inn, 'findById/party');
    }

    /**
     * Поиск данных банка по БИК
     * @param $bik - БИК банка
     * @return false|mixed
     */
    public static function getBank($bik)
    {
        $component = new self();
        return $component->getData($bik, 'findById/bank');
    }

    /**
     * Поиск адресов
     * @param string $address - строка поиска
     * @param int $count - количество вариантов
     * @return false|mixed
     */
    public static function getFias($address, $count = 3)
    {
        $component = new self();
        return $component->getData($address, 'suggest/fias', ['count' => $count]);
    }

    /**
     * Общий метод поиска
     * @param $query - запрос поиска
     * @param $endpoint - точка входа поиска
     * @param int[] $param - дополнительные параметры
     * @return false|mixed
     */
    public function getData($query, $endpoint, $param = ['count' => 1])
    {
        if (DADATA_API_KEY == '') return 'Не указан ключ авторизации';
        $count = 1;
        $post = [
            'query' => $query
        ];
        if (!empty($param)) {
            $post = array_merge($post, $param);
            if (!empty($param['count'])) $count = $param['count'];
        }
        $params = [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
                "Authorization: Token " . DADATA_API_KEY
            ],
            'post' => $post
        ];
        return $this->sendRequest($endpoint, $params, $count);
    }

    /**
     * Отправка запроса
     * @param $endpoint
     * @param array $params
     * @param int $count
     * @return false|mixed
     */
    public function sendRequest($endpoint, $params = [], $count = 1)
    {
        $curl = curl_init($this->url . $endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if (!empty($params['headers'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $params['headers']);
        }
        if (!empty($params['post'])) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params['post']));
        }
        $response = curl_exec($curl);
        curl_close($curl);

        $result = false;
        if ($response) {
            if ($response = json_decode($response, true)) {
                if (is_array($response) && !empty($response['suggestions'][0])) {
                    if ($count == 1) {
                        $result = $response['suggestions'][0];
                    } else {
                        $result = $response['suggestions'];
                    }
                }
            }
        }
        return $result;
    }
}