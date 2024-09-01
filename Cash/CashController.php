<?php

namespace Cash;

use Base\BaseController;
use Exception;
use Logic\ValidationData;
use Cash\CashModel;

class Cash
{
    private $cashModel;


    public function __construct()
    {
        $this->cashModel = new CashModel();
    }

    public function processRequests(): void
    {
        $messages= $this->cashModel->get();


        while ($message = $messages->fetch_assoc()) {
            $id = intval($message['id']);
            $messageId = $message['message_id'];
            $chatId = $message['chat_id'];


            $readyRequests = json_decode($message['ready_requests'], true);


            $amounts = isset($readyRequests['result']['amounts']) ? intval($readyRequests['result']['amounts']) : 0;

            $calculatedAmounts = $this->calculateAmount($amounts);


            $amountsString = implode(', ', array_map(
                function ($v, $k) {
                    return "$k: $v";
                },
                $calculatedAmounts,
                array_keys($calculatedAmounts)
            ));
            $processed = intval($message['processed']);

            $valid = new ValidationData($id, $messageId, $chatId, [], $amountsString, null, $processed);
            $data = $valid->createOutMessage();



            $this->cashModel->markMessageProcessed('requests',$id);
        }
    }

    private function calculateAmount($readyRequests) :array
    {
        // Процентные распределения
        $percentages = [
            "car" => 12,
            "apartment" => 20,
            "utilities" => 4,
            "communication" => 2,
            "food" => 18,
            "gas" => 9,
            "restaurant" => 7,
            "safety_cushion" => 10,
            "savings" => 10,
            "personal" => 9
        ];

        // Вычисляем суммы
        $amounts = [];
        foreach ($percentages as $category => $percentage) {
            $amounts[$category] = ($readyRequests * $percentage) / 100;
        }

        return $amounts;
    }
}
