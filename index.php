<?php

use Badoo\SoftMocks;
use \raylib\ {
    Collision,
    Color,
    Draw,
    Input\Key,
    Rectangle,
    Text,
    Timming,
    Vector2,
    Window
};

//define('SOFTMOCKS_ROOT_PATH', "C:\\");
define('SOFT_MOCKS_DEBUG', true);
require_once __DIR__ . '/vendor/badoo/soft-mocks/src/bootstrap.php';
require_once \Badoo\SoftMocks::rewrite(__DIR__ . '/src/PlayerSprite.php');
require_once \Badoo\SoftMocks::rewrite(realpath(__DIR__ . '/vendor/autoload.php'));
require_once \Badoo\SoftMocks::rewrite(__DIR__ . '/src/RayMocks.php');

\Badoo\SoftMocks::init();
//\raylib\helpers\RayMocks::applyMocks();

// Initialization
//--------------------------------------------------------------------------------------
$screenWidth  = 800;
$screenHeight = 450;
$lightGray    = new Color(245, 245, 245, 255);
$gray         = new Color(200, 200, 200, 255);


Window::init($screenWidth, $screenHeight, "raylib [core] example - basic window");

Timming::setTargetFps(60);
//--------------------------------------------------------------------------------------
class WorldState extends \raylib\Helpers\AppState {
    protected string $id = 'World';

    /**
     * @var \raylib\Helpers\PlayerSprite
     */
    private \raylib\Helpers\PlayerSprite $player;

    public function __construct()
    {
        $tex = new \raylib\Texture(__DIR__ . '/resources/characters/male_01_1.png');
        $this->player = new \raylib\Helpers\PlayerSprite($tex, 32, 32);
    }

    public function update(float $tpf)
    {
        $this->player->update($tpf);
    }

    public function render()
    {
        $this->player->render();
    }

    public function postRender()
    {

    }
}

$app = new \raylib\Helpers\Application();
$mgr = new \raylib\Helpers\StateManager($app);
$worldState = new WorldState();
try {
    $mgr->attach($worldState);
} catch (Exception $e) {
    echo 'Brah we got a problem';
    exit;
}

// Main game loop
while (!Window::shouldClose())    // Detect window close button or ESC key
{
    // Update
    //----------------------------------------------------------------------------------
    // TODO: Update your variables here
    //----------------------------------------------------------------------------------
//    \raylib\helpers\RayMocks::applyMocks();
    if (Key::isPressed(KEY::RIGHT_ALT)) {
        \raylib\helpers\RayMocks::reloadMocks();
    }

    // Draw
    //----------------------------------------------------------------------------------
    Draw::begin();

    Draw::clearBackground($lightGray);

    $app->update();

    Text::drawFps(0, 0);

    Draw::end();
    //----------------------------------------------------------------------------------
}

// De-Initialization
//--------------------------------------------------------------------------------------
Window::close();        // Close window and OpenGL context
//--------------------------------------------------------------------------------------