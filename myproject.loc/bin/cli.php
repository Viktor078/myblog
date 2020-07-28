<?php
require __DIR__ .'/../vendor/autoload.php';
try {
    unset($argv[0]);



    $className = '\\MyProject\\Cli\\' . array_shift($argv);
    if (!class_exists($className)) {
        throw new \MyProject\Exceptions\CliException('Class "' . $className . '" not found');
    }


    $params = [];
    foreach ($argv as $argument) {
        preg_match('/^-(.+)=(.+)$/', $argument, $matches);
        if (!empty($matches)) {
            $paramName = $matches[1];
            $paramValue = $matches[2];

            $params[$paramName] = $paramValue;
        }
    }
$checkAbstractClass= new \ReflectionClass($className);
    if(!$checkAbstractClass->isSubclassOf(\MyProject\Cli\AbstractCommand::class)) {
        throw new \MyProject\Exceptions\CliException('Class' .$className .'is not subclass of AbstractCommand');
    }

    $class = new $className($params);
    $class->execute();
} catch (\MyProject\Exceptions\CliException $e) {
    echo 'Error: ' . $e->getMessage();
}