<?php


require_once realpath(__DIR__ . '/Base/BaseController.php');
require_once realpath(__DIR__ . '/Base/BaseModel.php');
require_once realpath(__DIR__ . '/Logic/LogicController.php');
require_once realpath(__DIR__ . '/Logic/LogicModel.php');
require_once realpath(__DIR__ . '/Logic/ValidationData.php');
require_once realpath(__DIR__ . '/config.php');
require_once realpath(__DIR__ . '/SendMessage/SendModel.php');
require_once realpath(__DIR__ . '/SendMessage/ControllerSendMessage.php');
require_once realpath(__DIR__ . '/DataBase.php');
//require_once realpath(__DIR__ . '/Cash/CashController.php');
//require_once realpath(__DIR__ . '/Cash/CashModel.php');


use Base\BaseModel;
use Logic\Logic;
use Telegramm\ControllerSendMessage;
use Cash\Cash;



try {


    $requestController = new Logic();
    $requestController->process();
/*
    $cashController = new Cash();
    $cashController->processRequests();


    $sendMessage = new ControllerSendMessage();
    $sendMessage->processOutMessages();
*/

} catch (Exception $e) {

    //TODO логи
    echo 'Error send Message: ' . $e->getMessage();
}
