<?php
namespace Logic;


class Logic extends BaseController
{
    private $logicModel;

    public function __construct()
    {
        parent::__construct("in_messages", "message", "requests", "ready_requests");
        $this->logicModel = new LogicModel();

    }
    public function handleMessage($messageId, $chatId, $messageText, $requestData, $processed): void
    {

        if ($this->extractDataFromMessage($messageText)) {
            $valid = new ValidationData($messageId, '', $requestData, '', [], $requestData['type'], $processed);
            $data = $valid->createRequest();
            $this->logicModel->insert($data);
        } else {
            $valid = new ValidationData($messageId, $chatId, [], $messageText, [], null, $processed);
            $data = $valid->createOutMessage();
            $this->logicModel->insertOutMessage($data);
        }

        $this->logicModel->markMessageProcessed($messageId);
    }

    public function extractDataFromMessage($messageText): bool
    {
        $pattern = '/^(\d+(?:\.\d+)?)(?:\s*)?(\$|rub|грн|eur)$/i';

        // Если сообщение соответствует паттерну, возвращаем true
        if (preg_match($pattern, $messageText)) {
            return true;
        }

        // Иначе возвращаем false
        return false;
    }
}

