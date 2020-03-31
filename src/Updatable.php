<?php


namespace raylib\Helpers;


interface Updatable
{
    public function update(float $tpf) : void;
}