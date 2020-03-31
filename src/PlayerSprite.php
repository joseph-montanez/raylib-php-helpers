<?php


namespace raylib\Helpers;


use raylib\Color;
use raylib\Draw;
use raylib\Input\Key;
use raylib\Rectangle;
use raylib\Texture;
use raylib\Timming;
use raylib\Vector2;

class PlayerSprite
{
    const DIRECTION_NONE = 0b000001;
    const DIRECTION_IDLE = 0b000010;
    const DIRECTION_NORTH = 0b000100;
    const DIRECTION_SOUTH = 0b001000;
    const DIRECTION_WEST = 0b010000;
    const DIRECTION_EAST = 0b100000;


    const CONST_FRAME_TIME = 0.02;

    /**
     * @var int The direction the $this is facing.
     */
    public int $dir;

    /**
     * @var int Which animation frame is the $this on.
     */
    public int $frame;

    /**
     * @var int How many frames to play per second.
     */
    public int $frameFps;

    /**
     * @var int The sprite width in pixels.
     */
    public int $frameWidth;

    /**
     * @var int The sprite height in pixels.
     */
    public int $frameHeight;

    /**
     * @var \raylib\Vector2 The position of the $this, might need to adjust based on origin.
     */
    public Vector2 $pos;

    /**
     * @var \raylib\Texture The $this texture.
     */
    public Texture $tex;

    /**
     * @var \raylib\Rectangle The tile to draw.
     */
    public Rectangle $sourceRec;

    /**
     * @var \raylib\Rectangle Where to draw the tile.
     */
    public Rectangle $destRec;

    /**
     * @var \raylib\Color
     */
    public Color $color;

    /**
     * @var \raylib\Color
     */
    private Color $black;

    public function __construct(\raylib\Texture $tex, int $width, int $height)
    {
        $this->color = new \raylib\Color(255, 255, 255, 255);
        $this->black = new \raylib\Color(0, 0, 0, 0);

        $this->dir = self::DIRECTION_IDLE;
        $this->frame = 0;
        $this->frameFps = 3;
        $this->frameWidth = $width;
        $this->frameHeight = $height;
        $this->pos = new \raylib\Vector2(0, 0);
        $this->tex = $tex;
        $this->sourceRec = new \raylib\Rectangle(0, 0, (float)$width, (float)$height);
        $this->destRec = new \raylib\Rectangle(
            -$width / 2,
            -$height / 2,
            $width,
            $height
        );

    }

    public function render()
    {
        if ($this->dir & self::DIRECTION_WEST) {
            $this->sourceRec->setY($this->frameHeight * 1);
        }
        if ($this->dir & self::DIRECTION_EAST) {
            $this->sourceRec->setY($this->frameHeight * 2);
        }
        if ($this->dir & self::DIRECTION_SOUTH || $this->dir & self::DIRECTION_IDLE) {
            $this->sourceRec->setY($this->frameHeight * 0);
        }
        if ($this->dir & self::DIRECTION_NORTH) {
            $this->sourceRec->setY($this->frameHeight * 3);
        }

        if (($this->dir & self::DIRECTION_IDLE) == self::DIRECTION_IDLE) {
            $this->sourceRec->setX($this->frameHeight * 0);
        } else {
            $this->sourceRec->setX($this->frameHeight * $this->frame);
        }


        $this->tex->drawPro($this->sourceRec, $this->destRec, $this->pos, 0.0, $this->color);

        // Draw center point of position
        \raylib\Draw::rectangle((int)round(-$this->pos->getX() - 1), (int)round(-$this->pos->getY() - 1), 2, 2, $this->black);
        \raylib\Draw::rectangleLines(
            (int)round(-$this->pos->getX()) - ($this->frameWidth / 2),
            (int)round(-$this->pos->getY()) - ($this->frameHeight / 2),
            $this->frameWidth,
            $this->frameHeight,
            $this->black);
    }

    public function update(float $tpf)
    {
        $is_idle = 1;
        // Set the $this direction, useful for when other key mappings will be added
        if (\raylib\Input\Key::isDown(\raylib\Input\Key::A)) {
            $this->dir |= self::DIRECTION_WEST;
        } else {
            $this->dir &= ~self::DIRECTION_WEST;
        }
        if (\raylib\Input\Key::isDown(\raylib\Input\Key::D)) {
            $this->dir |= self::DIRECTION_EAST;
        } else {
            $this->dir &= ~self::DIRECTION_EAST;
        }
        if (\raylib\Input\Key::isDown(\raylib\Input\Key::W)) {
            $this->dir |= self::DIRECTION_NORTH;
        } else {
            $this->dir &= ~self::DIRECTION_NORTH;
        }
        if (\raylib\Input\Key::isDown(\raylib\Input\Key::S)) {
            $this->dir |= self::DIRECTION_SOUTH;
        } else {
            $this->dir &= ~self::DIRECTION_SOUTH;
        }


        $frames = (int)round(\raylib\Timming::getTime() / (1 / (float)$this->frameFps));
        $this->frame = $frames % $this->frameFps;
    
        $t = $tpf / self::CONST_FRAME_TIME; // expect 60fps, adjust if not reached
        $x = 0;
        $y = 0;
        $mov_x = 1.0; // * ((float) GetScreenHeight() / (float) GetScreenWidth());
        $mov_y = 1.0;
    
    
        if ($this->dir & self::DIRECTION_WEST) {
            $x = $mov_x * $t;
        }
        if ($this->dir & self::DIRECTION_EAST) {
            $x = -$mov_x * $t;
        }
        if ($this->dir & self::DIRECTION_NORTH) {
            $y = $mov_y * $t;
        }
        if ($this->dir & self::DIRECTION_SOUTH) {
            $y = -$mov_y * $t;
        }
    
        if ($x == 0 && $y == 0) {
            $this->dir |= self::DIRECTION_IDLE;
        } else {
            $this->dir &= ~self::DIRECTION_IDLE;
        }

        $this->pos->setX($this->pos->getX() + $x);
        $this->pos->setY($this->pos->getY() + $y);
    }
}