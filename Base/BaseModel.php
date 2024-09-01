<?php
namespace Base;

use Connection\Database;
use Exception;

class BaseModel
{
    private $dbLink;

    private $tableName;
    private $kind;
    private $insertTable;


    public function __construct()
    {
        $this->dbLink = Database::getInstance()->getConnection();

    }

    public function setTableName($tableName): void
    {
        $this->tableName = $tableName;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function setInsertTable($insertTable): void
    {
        $this->insertTable = $insertTable;
    }

    public function setTypeOfData($typeOfData): void
    {
        $this->typeOfData = $typeOfData;
    }

    public function get($kind, $tableName): array
    {
        if (!$tableName || !$kind) {
            throw new Exception("Table name or kind is not set.");
        }

        $sql = "SELECT message_id, chat_id, {$kind}, processed FROM {$tableName} WHERE processed = 0";
        $stmt = $this->dbLink->prepare($sql);

        if (!$stmt) {
            throw new Exception($this->dbLink->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            throw new Exception($stmt->error);
        }

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        $stmt->close();

        return $messages;
    }

    public function insert($data): bool
    {
        $sql = "INSERT INTO {$this->insertTable} (message_id, chat_id, {$this->typeOfData}, processed) VALUES (?, ?, ?, ?)";
        $stmt = $this->dbLink->prepare($sql);

        if (!$stmt) {
            throw new Exception($this->dbLink->error);
        }

        // Один вызов bind_param
        $stmt->bind_param('sssi', $data['message_id'], $data['chat_id'], $data[$this->typeOfData], $data['processed']);

        $result = $stmt->execute();

        if (!$result) {
            throw new Exception($stmt->error);
        }

        $stmt->close();

        return $result;
    }


    public function markMessageProcessed(string $message_id): void
    {
        if (!$this->tableName) {
            throw new Exception("Table name must be set before marking messages as processed.");
        }

        $stmt = $this->dbLink->prepare("UPDATE {$this->tableName} SET processed = 1 WHERE message_id = ?");
        if (!$stmt) {
            throw new Exception($this->dbLink->error);
        }
        $stmt->bind_param('s', $message_id);
        $stmt->execute();
        $stmt->close();
    }
}
