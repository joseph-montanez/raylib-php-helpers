<?php

require_once __DIR__ . '/vendor/autoload.php';

$playerSpriteReflection = new ReflectionClass('\raylib\Helpers\PlayerSprite');
$classObjDef = [
    'class' => $playerSpriteReflection->getName(),
    'methods' => [],
];

$code = file_get_contents($playerSpriteReflection->getFileName());
$lines = preg_split("/\\r\\n|\\r|\\n/", $code);

foreach ($playerSpriteReflection->getMethods() as $method) {
    $param_list = [];
    foreach ($method->getParameters() as $parameter) {

        $param_str = '';

        if ($parameter->getType()) {
            $param_str .= $parameter->getType()->getName() . ' ';
        }
        if ($parameter->isPassedByReference()) {
            $param_str .= '&';
        }
        $param_str .= '$' . $parameter->getName() . ' ';
        if ($parameter->isOptional()) {

            try {
                $value = $parameter->getDefaultValue();
                $param_str .= '= ' . $value;
            } catch (Exception $e) {

            }
        }
        $param_list [] = trim($param_str);
    }

    $method_lines = array_slice($lines, $method->getStartLine(), $method->getEndLine() - $method->getStartLine());

    $code = trim(implode("\n", $method_lines), "{} \t\n\r\0\x0B");
    $classObjDef['methods'][$method->getName()] = [
        'args' => implode(', ', $param_list),
        'code' => $code,
    ];
}

echo json_encode($classObjDef);