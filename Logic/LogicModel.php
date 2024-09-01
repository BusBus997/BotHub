<?php
namespace Logic;

use Base\BaseModel;

class LogicModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct(); // Передаем только тип (в данном случае null)


        // Устанавливаем параметры для модели
        $this->setTableName("in_messages");
        $this->setKind('message');
        $this->setInsertTable('requests');
        $this->setTypeOfData('ready_requests');
    }

    public function insertOutMessage($data): bool
    {

        $this->setInsertTable('out_messages');
        $this->setTypeOfData('ready_out_messages');
        $data['ready_out_messages'] = 'Ваш запрос некорректен: ' . ($data['ready_out_messages'] ?? '');
        return parent::insert($data);
    }
}
