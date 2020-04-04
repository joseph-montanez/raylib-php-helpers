<?php
require_once __DIR__ . '/vendor/autoload.php';

use raylib\Helpers\MainLoop;

$mainloop = new MainLoop(120);

$time = microtime(true);
while ($mainloop->shouldExit()) {
    $mainloop->run(function ($mainloop) use ($time) {

        time_nanosleep(0, 1000000000 / 300);
    });


    if (microtime(true) - $time > 1.0) {
        echo 'FPS: ', $mainloop->getFps(), PHP_EOL;
        $mainloop->setExit(true);
    }
}