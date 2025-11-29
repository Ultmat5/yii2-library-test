<?php

namespace app\services;

use Yii;

class SmsService
{
    /**
     * @var string API ключ
     */
    private $apiKey;

    /**
     * @var string URL
     */
    private $apiUrl;

    public function __construct()
    {
        $config = Yii::$app->params['smspilot'] ?? [];

        $this->apiKey = $config['apikey'] ?? '';
        $this->apiUrl = $config['url'] ?? 'https://smspilot.ru/api.php';
    }

    /**
     * @param string $phone Номер телефона
     * @param string $message Текст сообщения
     */
    public function send(string $phone, string $message)
    {
        if (empty($this->apiKey)) {
            Yii::warning('SMS Pilot: Не указан API ключ в config/params.php', 'sms');
            return;
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($cleanPhone, 0, 1) === '8') {
            $cleanPhone = '7' . substr($cleanPhone, 1);
        }

        $params = [
            'send' => $message,
            'to' => $cleanPhone,
            'apikey' => $this->apiKey,
        ];

        $requestUrl = $this->apiUrl . '?' . http_build_query($params);

        try {
            $result = file_get_contents($requestUrl);

            if ($result !== false && strpos($result, 'SUCCESS') === 0) {
                Yii::info("SMS успешно отправлено на $cleanPhone. Ответ: $result", 'sms');
            } else {
                Yii::error("SMS Pilot вернул ошибку: $result", 'sms');
            }

        } catch (\Exception $e) {
            Yii::error("Ошибка соединения с SMS Pilot: " . $e->getMessage(), 'sms');
        }
    }
}