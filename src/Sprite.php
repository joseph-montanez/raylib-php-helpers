<?php

namespace raylib\Helpers;


use raylib\Vector2;

class Sprite implements Objectable, Updatable, Renderable
{
    public float $scale = 1.0;

    public float $rotation = 0;

    /**
     * @var \raylib\Texture The $this texture.
     */
    public \raylib\Texture $tex;

    /**
     * @var \raylib\Color The $this texture.
     */
    public \raylib\Color $tint;

    /**
     * @var \raylib\Vector2 The $this texture.
     */
    public \raylib\Vector2 $pos;

    public function __construct(\raylib\Texture $tex, \raylib\Vector2 $pos, \raylib\Color $tint)
    {
        $this->tex = $tex;
        $this->pos = $pos;
        $this->tint = $tint;
    }

    public function update(float $tpf): void
    {

    }

    public function render(): void
    {
        $this->tex->drawEx($this->pos, $this->rotation, $this->scale, $this->tint);
    }

    public function getX(): float
    {
        return $this->pos->x;
    }

    public function setX(float $x)
    {
        $this->pos->x = $x;
    }

    public function getY(): float
    {
        return $this->pos->x;
    }

    public function setY(float $y)
    {
        $this->pos->y = $y;
    }

    public function getPos(): \raylib\Vector2
    {
        $this->pos;
    }

    public function setPos(\raylib\Vector2 $pos)
    {
        $this->pos = $pos;
    }

    public function getRotation(): float
    {
        return $this->rotation;
    }

    public function setRotation(float $rotation)
    {
        $this->rotation = $rotation;
    }

    public function getScale(): float
    {
        return $this->scale;
    }

    public function setScale(float $scale)
    {
        $this->scale = $scale;
    }
}