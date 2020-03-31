<?php

namespace raylib\Helpers\Sprites;

use raylib\Rectangle;

class AnimatedSprite extends \raylib\Helpers\Sprites\TiledSprite
{
    /**
     * @var \raylib\Helpers\Sprites\Animation[] $animations to play
     */
    public array $animations = [];

    public string $currentAnimation = '';

    public function __construct(\raylib\Texture $tex, \raylib\Vector2 $pos, \raylib\Color $tint, $frameWidth, $frameHeight, array $animations, string $currentAnimation = '')
    {
        parent::__construct($tex, $pos, $tint, $frameWidth, $frameHeight, 0);

        $this->currentAnimation = $currentAnimation;

        foreach ($animations as $animation) {
            $this->addAnimation($animation);
        }

    }

    public function update(float $tpf): void
    {
        $animation = $this->animations[$this->currentAnimation];

        $frames = (int)round(\raylib\Timming::getTime() / (1 / (float)$animation->frameFps));
        $this->frame = $animation->startFrame + ($frames % $animation->frameFps);
    }

    public function addAnimation(Animation $animation)
    {
        if (strlen($this->currentAnimation) === 0 && count($this->animations) === 0) {
            $this->currentAnimation = $animation->id;
        }

        $this->animations[$animation->id] = $animation;
    }

}