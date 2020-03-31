<?php

namespace raylib\Helpers\States;



use raylib\Text;
use raylib\Window;

class PauseState extends \raylib\Helpers\AppState {
    protected string $id = 'pause';

    private \raylib\Color $textColor;
    private int $fontSize = 64;
    private int $width = 0;
    private int $x = 0;
    private int $y = 0;

    public function __construct()
    {
        $this->textColor = new \raylib\Color(150, 150, 150, 255);
    }

    public function update(float $tpf)
    {
        $this->width = \raylib\Text::measure("Paused", $this->fontSize);

        if (\raylib\Input\Key::isDown(\raylib\Input\Key::A)) {
            $this->x--;
        }
        if (\raylib\Input\Key::isDown(\raylib\Input\Key::D)) {
            $this->x++;
        }
        if (\raylib\Input\Key::isDown(\raylib\Input\Key::W)) {
            $this->y--;
        }
        if (\raylib\Input\Key::isDown(\raylib\Input\Key::S)) {
            $this->y++;
        }

    }

    public function render()
    {
        \raylib\Text::draw("Paused",
            ((\raylib\Window::getScreenWidth() - $this->width) / 2) + $this->x,
            ((\raylib\Window::getScreenHeight() - $this->fontSize) / 2) + $this->y,
            $this->fontSize,
            $this->textColor
        );
    }

    public function postRender()
    {

    }
}