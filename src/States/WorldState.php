<?php

namespace raylib\Helpers\States;



use raylib\Color;
use raylib\Helpers\Sprites\AnimatedSprite;
use raylib\Helpers\Sprites\Animation;
use raylib\Vector2;

class WorldState extends \raylib\Helpers\AppState {
    protected string $id = 'world';

    /**
     * @var \raylib\Helpers\PlayerSprite
     */
    private \raylib\Helpers\PlayerSprite $player;

    /**
     * @var \raylib\Helpers\Sprites\AnimatedSprite
     */
    private AnimatedSprite $tile;

    public function __construct()
    {
        $tex = new \raylib\Texture(__DIR__ . '/../../resources/characters/male_01_1.png');
        $this->player = new \raylib\Helpers\PlayerSprite($tex, 32, 32);
        $this->tile = new AnimatedSprite(
            $tex,
            new Vector2(200, 200),
            new Color(255,255,255,255),
            32,
            32,
            [
                new Animation('walk', 0, 2, 3)
            ],
        );
    }

    public function update(float $tpf)
    {
        $this->player->update($tpf);
        $this->tile->update($tpf);
    }

    public function render()
    {
        $this->player->render();
        $this->tile->render();
    }

    public function postRender()
    {

    }
}