<?php

require 'vendor/autoload.php';

use JansenFelipe\CnpjGratis\CnpjGratis as CnpjGratis;
use JansenFelipe\Utils\Utils as Utils;
use JansenFelipe\Utils\Mask as Mask;

try {

    if (!isset($_POST['cnpj']) || !isset($_POST['captcha']) || !isset($_POST['cookie']))
        throw new Exception('Informe todos os campos', 99);

    $return = CnpjGratis::consulta($_POST['cnpj'], $_POST['captcha'], $_POST['cookie']);

    $return['cep'] = Utils::mask($return['cep'], Mask::CEP);
    $return['code'] = 0;
} catch (Exception $e) {
    $return = array('code' => $e->getCode(), 'message' => $e->getMessage());
}

header('Content-Type: application/json');
echo json_encode($return);
