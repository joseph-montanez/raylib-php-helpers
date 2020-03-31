<?php


namespace raylib\Helpers\Sprites;


class Animation
{
    /**
     * @var int How many frames to play per second.
     */
    public int $frameFps;

    /**
     * @var int Which frame to start on
     */
    public int $startFrame;

    /**
     * @var int Which frame to start on
     */
    public int $endFrame;
    /**
     * @var string
     */
    public string $id;

    public function __construct(string $id, int $startFrame, int $endFrame, int $frameFps)
    {
        $this->id = $id;
        $this->startFrame = $startFrame;
        $this->endFrame = $endFrame;
        $this->frameFps = $frameFps;
    }

}