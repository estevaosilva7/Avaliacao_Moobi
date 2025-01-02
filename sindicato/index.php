<?php
spl_autoload_register(function ($class) {
    $SFile = __DIR__ . "/app/Controllers/$class.php";
    if (file_exists($SFile)) {
        include $SFile;
    } else {
        throw new Exception("Classe '$class' não encontrada.");
    }
});

$aDados = array_merge($_GET, $_POST);
$sPath = isset($aDados['path']) ? explode('/', $aDados['path']) : ['usuario', 'index'];
$sControllerName = ucfirst($sPath[0]) . 'Controller';
$sMetodo = $sPath[1] ?? 'index';

try {
    if (class_exists($sControllerName)) {
        $mController = new $sControllerName();
    } else {
        throw new Exception("Controlador '$sControllerName' não encontrado.");
    }
    if (method_exists($mController, $sMetodo)) {
	    $mController->$sMetodo($aDados);
    } else {
        throw new Exception("Método '$sMetodo ' não encontrado.");
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
