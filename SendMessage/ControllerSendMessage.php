<?php
namespace Telegramm;
class ControllerSendMessage
{

    private $sendModel;

    public function __construct()
    {
        $this->sendModel = new SendModel();
    }

    public function processOutMessages()
    {


        $result = $this->sendModel->getUnprocessedOutMessages();


        while ($message = $result->fetch_assoc()) {
            $messageId = $message['message_id'];
            $send_data = [
                'chat_id' => $message['chat_id'],
                'text' => $message['ready_out_messages'],
            ];


            $this->sendTelegram('sendMessage', $send_data);
            $this->sendModel->markMessageProcessed($messageId);
        }

    }

    private function sendTelegram($method, $data, $headers = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.telegram.org/bot' . TOKEN . '/' . $method,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array_merge(["Content-Type: application/json"], $headers)
        ]);


        $response = curl_exec($curl);


        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new Exception($error);
        } else {
            curl_close($curl);
            return $response;
        }
    }
}




