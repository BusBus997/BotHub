<?php
namespace Logic;

class ValidationData
{

    protected $message_id;
    protected $chat_id;
    protected $processed;
    private $ready_out_messages;
    private $response;
    private $ready_requests;
    private $type;

    public function __construct( string $message_id,
                                 string $chat_id = '',
                                 array $ready_requests = [],
                                 string $ready_out_messages = '',
                                 array $response = [],
                                 ?int $type = null,
                                 int $processed = 0)
    {

        $this->chat_id = $chat_id;
        $this->message_id = $message_id;
        $this->ready_requests = json_encode($ready_requests);
        $this->ready_out_messages = $ready_out_messages;
        $this->response = $response;
        $this->type = $type;
        $this->processed = $processed;
    }

    public function createRequest(): array
    {
        return [
            'message_id' => $this->message_id,
            'ready_requests' => $this->ready_requests,
            'processed' => $this->processed,
            'type' =>  $this->type,
        ];
    }

    public function createOutMessage(): array
    {
        return [

            'message_id' => $this->message_id,
            'chat_id' => $this->chat_id,
            'ready_out_messages' => $this->ready_out_messages,
            'processed' => $this->processed
        ];
    }
    public function createResponse(): array
    {
        return [

            'message_id' => $this->message_id,
            'chat_id' => $this->chat_id,
            'response' => $this->response,
            'processed' => $this->processed
        ];
    }

}
//

