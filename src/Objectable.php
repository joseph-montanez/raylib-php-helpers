<?php


namespace raylib\Helpers;


interface Objectable
{
    public function getRotation() : float;
    public function setRotation(float $rotation);

    public function getScale() : float;
    public function setScale(float $scale);

    public function getX() : float;
    public function setX(float $x);

    public function getY() : float;
    public function setY(float $y);

    public function getPos() : \raylib\Vector2;
    public function setPos(\raylib\Vector2 $pos);
}