<?php

namespace raylib\Helpers\Sprites;

use raylib\Rectangle;

class TiledSprite extends \raylib\Helpers\Sprite
{

    /**
     * @var int Which frame to render.
     */
    public int $frame = 0;

    /**
     * @var int Which frame to render.
     */
    public int $lastFrame = 0;

    /**
     * @var int The sprite width in pixels.
     */
    public int $frameWidth = 32;

    /**
     * @var int The sprite height in pixels.
     */
    public int $frameHeight = 32;

    /**
     * @var \raylib\Rectangle The tile to draw.
     */
    public \raylib\Rectangle $sourceRec;

    /**
     * @var \raylib\Rectangle Where to draw the tile.
     */
    public \raylib\Rectangle $destRec;

    public function __construct(\raylib\Texture $tex, \raylib\Vector2 $pos, \raylib\Color $tint, $frameWidth, $frameHeight, $frame)
    {
        parent::__construct($tex, $pos, $tint);

        $this->frameWidth = $frameWidth;
        $this->frameHeight = $frameHeight;
        $this->frame = $frame;
        $this->lastFrame = $frame;
        $this->setTileSourceRec($frame);
        $this->destRec = new \raylib\Rectangle(0, 0, $this->frameWidth, $this->frameHeight);
        $this->setX($pos->x);
        $this->setY($pos->y);
    }

    public function setX(float $x){
        $this->pos->x = 0;
        $this->destRec->x = $x;
    }

    public function getX():float{
        return $this->destRec->x;
    }

    public function setY(float $y){
        $this->pos->y = 0;
        $this->destRec->y = $y;
    }

    public function getY():float{
        return $this->destRec->y;
    }

    public function setTileSourceRec($frame)
    {
        $yTiles = $this->tex->height / $this->frameHeight;
        $xTiles = $this->tex->width / $this->frameWidth;

        $x = $this->frame % $xTiles;
        $y = ($this->frame / $xTiles) % $yTiles;

        $this->sourceRec = new \raylib\Rectangle($x * $this->frameWidth, $y * $this->frameHeight, $this->frameWidth, $this->frameHeight);
    }

    public function render(): void
    {
        if ($this->frame !== $this->lastFrame) {
            $this->setTileSourceRec($this->frame);
            $this->lastFrame = $this->frame;
        }

        // TODO: how to handle scaling
        $this->tex->drawPro($this->sourceRec, $this->destRec, $this->pos, $this->rotation, $this->tint);
    }

}