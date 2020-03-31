<?php

namespace raylib\Helpers\States;


use raylib\Color;
use raylib\Draw;
use raylib\Text;
use raylib\Timming;

class ProfilerState extends \raylib\Helpers\AppState
{
    protected string $id = 'profiler';

    protected $profiles = [];
    protected $fps = [];
    protected $lineColor;

    public function __construct()
    {
        $this->lineColor = new Color(200, 0, 0, 255);
    }

    public function update(float $tpf)
    {
        $this->fps[] = Timming::getFPS();
    }

    public function render()
    {
        $last_100_fps = array_slice($this->fps, -100);
        foreach ($last_100_fps as $n => $fps) {
            Draw::line($n + 20, 100, $n + 20, 100 - $fps, $this->lineColor);
        }
        Draw::rectangleLines(19, 20, 100, 100, $this->lineColor);
        Text::drawFps(35, 100);
    }

    public function postRender()
    {
        $prof = $this->getApplication()->getAppProfiler();

        if ($prof) {
            foreach ($prof::getTimers() as $timerKey) {
                $timer = $prof::read($timerKey);
                if (!isset($this->profiles[$timerKey])) {
                    $this->profiles[$timerKey] = [];
                }
                $this->profiles[$timerKey][] = (float)$timer;
            }

        }
    }

    public function cleanup()
    {
        $max_frame_time = 1000 / 60;
        foreach ($this->profiles as $profileKey => $profileTimers) {
            $microseconds = round((array_sum($profileTimers) / count($profileTimers)) * 1000);
            echo str_pad($profileKey, 60, " "), " | ",
            str_pad($microseconds .' microseconds', 20, " "), " | ",
            round(($microseconds / ($max_frame_time * 1000)) * 100, 2),
            '% of total frame time', PHP_EOL;
        }

    }
}