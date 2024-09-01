<?php
namespace Base;

use Exception;

abstract class BaseController
{
    protected $baseModel;
    protected $tableName;
    protected $kind;
    protected $insertTable;
    protected $typeOfData;

    public function __construct($tableName, $kind, $insertTable, $typeOfData)
    {
        // Инициализация BaseModel с минимальными параметрами
        $this->baseModel = new BaseModel();

        // Установка параметров после инициализации
        $this->baseModel->setTableName($tableName);
        $this->baseModel->setKind($kind);
        $this->baseModel->setInsertTable($insertTable);
        $this->baseModel->setTypeOfData($typeOfData);

        // Сохранение параметров в свойства класса
        $this->tableName = $tableName;
        $this->kind = $kind;
        $this->insertTable = $insertTable;
        $this->typeOfData = $typeOfData;
    }

    public function process(): void
    {
        if (!$this->baseModel) {
            throw new Exception("BaseModel is not initialized.");
        }

        if (!$this->kind || !$this->tableName) {
            throw new Exception("Kind or TableName is not set.");
        }

        $messages = $this->baseModel->get($this->kind, $this->tableName);

        foreach ($messages as $message) {
            $messageId = $message['message_id'];
            $chatId = $message['chat_id'];
            $messageText = $message[$this->kind];
            $processed = intval($message['processed']);

            $requestData = $this->extractDataFromMessage($messageText);

            $this->handleMessage($messageId, $chatId, $messageText, $requestData, $processed);
        }
    }

    protected function extractDataFromMessage($messageText): array
    {
        return []; // Здесь нужно будет реализовать конкретную логику извлечения данных
    }

    abstract protected function handleMessage($messageId, $chatId, $messageText, $requestData, $processed): void;
}
