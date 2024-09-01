<?php
namespace Telegramm;
use Connection\Database;
use mysqli;

class SendModel
{
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = Database::getInstance()->getConnection();
    }
    public function getUnprocessedOutMessages()
    {
        $sql = "SELECT id, message_id, chat_id, ready_out_messages, processed FROM out_messages WHERE processed = 0";
        $outMessage = $this->mysqli->prepare($sql);

        if (!$outMessage) {
            throw new Exception($this->mysqli->error);
        }

        $outMessage->execute();
        $result = $outMessage->get_result();
        $outMessage->close();

        return $result;
    }

    public function markMessageProcessed(string $messageId)
    {
        $stmt = $this->mysqli->prepare("UPDATE out_messages SET processed = 1 WHERE message_id = ?");
        if (!$stmt) {
            throw new Exception($this->mysqli->error);
        }
        $stmt->bind_param('i', $messageId);
        $stmt->execute();
        $stmt->close();
    }
}

