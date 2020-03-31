<?php


namespace raylib\Helpers;


use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;

class RayMocks
{
    public static function run(PlayerSprite $player)
    {
        $result = [
//            'TEST_CONSTANT_WITH_VALUE_42' => TEST_CONSTANT_WITH_VALUE_42,
//            'someFunc(2)' => someFunc(2),
//            'Example::doSmthStatic()' => Example::doSmthStatic(),
            'PlayerSprite->render()' => (new PlayerSprite($player->tex, $player->frameWidth, $player->frameHeight))->render(),
//            'Example::STATIC_DO_SMTH_RESULT' => Example::STATIC_DO_SMTH_RESULT,
        ];

        return $result;
    }

    public static function applyMocks()
    {
//        \Badoo\SoftMocks::redefineConstant('TEST_CONSTANT_WITH_VALUE_42', 43);
//        \Badoo\SoftMocks::redefineConstant('\Example::STATIC_DO_SMTH_RESULT', 'Example::STATIC_DO_SMTH_RESULT value changed');
//        \Badoo\SoftMocks::redefineMethod(PlayerSprite::class, 'render', '', 'return "Example::doSmthStatic() redefined";');
//        \Badoo\SoftMocks::redefineFunction('someFunc', '$a', 'return 55 + $a;');

        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        try {
            $ast = $parser->parse(file_get_contents(__DIR__ . '/PlayerSprite.php'));
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return;
        }

        $dumper = new NodeDumper;
        echo $dumper->dump($ast) . "\n";


        \Badoo\SoftMocks::redefineMethod(PlayerSprite::class, 'render', '', 'return "PlayerSprite->render() redefined";');
        \Badoo\SoftMocks::redefineMethod(PlayerSprite::class, 'update', '', 'return "PlayerSprite->update() redefined";');
    }

    public static function revertMocks()
    {
        \Badoo\SoftMocks::restoreAll();
    }

    public static function reloadMocks()
    {
        $reload_classes = [
            PlayerSprite::class,
        ];

        foreach ($reload_classes as $class) {
            exec(PHP_BINARY . ' ' . escapeshellarg(realpath(__DIR__ . '/../livereload.php')) . ' ' . escapeshellarg($class), $output, $return_val);

            if ($return_val === 0) {
                $json = $output[0];
                $data = json_decode($json, true);
                foreach ($data['methods'] as $method_name => $method) {
                    try {
                        \Badoo\SoftMocks::redefineMethod($data['class'], $method_name, $method['args'], $method['code']);
//                        echo $data['class'], '::', $method_name, ' - reloaded', PHP_EOL;
                    } catch (\Exception $e) {
                        echo $e->getMessage(), PHP_EOL;

                        \Badoo\SoftMocks::restoreMethod($data['class'], $method_name);

                        \Badoo\SoftMocks::restoreAll();

                        return false;
                    }
                }
                echo 'Reloaded ', $data['class'], PHP_EOL;
            }
        }

        return true;

    }
}