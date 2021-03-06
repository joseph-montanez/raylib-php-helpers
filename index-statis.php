<?php

use \raylib\ {
    Color,
    Draw,
    Input\Key,
    Text,
    Timming,
    Window
};

require_once __DIR__ . '/vendor/autoload.php';

// Initialization
//--------------------------------------------------------------------------------------
$screenWidth  = 800;
$screenHeight = 450;
$lightGray    = new Color(245, 245, 245, 255);
$gray         = new Color(200, 200, 200, 255);

Window::init($screenWidth, $screenHeight, "raylib [core] example - basic window");

Timming::setTargetFps(60);
//--------------------------------------------------------------------------------------
//-- States
$pauseState = new \raylib\Helpers\States\PauseState();
$worldState = new \raylib\Helpers\States\WorldState();
$profilerState = new \raylib\Helpers\States\ProfilerState();

//-- Apps
$app = new \raylib\Helpers\Application('world', [$worldState, $profilerState]);
$pauseApp = new \raylib\Helpers\Application('pause', [$pauseState, $profilerState]);

$pauseApp->setEnable(false);

//-- App Manager
$appMgr = new \raylib\Helpers\ApplicationManager([$app, $pauseApp]);

$slept = 0;
$time = microtime(true);
$fps = 0;
// Main game loop
while (!Window::shouldClose())    // Detect window close button or ESC key
{
    if ($slept > 10) {
        exit;
    }

    $fps++;


    if (microtime(true) - $time > 0.16 && microtime(true) - $time < 0.5) {
        echo "sleeping...", PHP_EOL;
        time_nanosleep(0, 1000000000 / 60 * 2);
    }

    if (microtime(true) - $time > 1.0) {
        $slept++;
        $time = microtime(true);
        echo 'FPS: ', $fps, ' - avg - ', Timming::getFPS(), PHP_EOL;
        $fps = 0;
    }

    // Update
    //----------------------------------------------------------------------------------
    // TODO: Update your variables here
    //----------------------------------------------------------------------------------
    if (Key::isPressed(Key::I)) {
        $app->getStateManager()->getState('profiler')->setEnabled(!$profilerState->isEnabled());
    }
    if (Key::isPressed(Key::UP)) {
        $app->setSpeed($app->getSpeed() + 0.5);
    }
    if (Key::isPressed(Key::DOWN)) {
        $app->setSpeed($app->getSpeed() - 0.5);
    }
    if (Key::isPressed(Key::P)) {
        $app->setPause(!$app->getPause());
        $pauseApp->setEnable(!$pauseApp->getEnable());
    }
    $appMgr->update();

    // Draw
    //----------------------------------------------------------------------------------
    Draw::begin();

    Draw::clearBackground($lightGray);

    $appMgr->render();

    Draw::end();
    //----------------------------------------------------------------------------------
}

// De-Initialization
//--------------------------------------------------------------------------------------
Window::close();        // Close window and OpenGL context
//--------------------------------------------------------------------------------------