<?php


namespace raylib\Helpers;


class MainLoop
{
    public int $oldTime;
    public int $fpsTime;
    public int $fps = 0;
    public int $fpsCounter = 0;
    public int $targetFps;
    public int $adjustedMinFps;
    public bool $exit = false;
    /**
     * @var float|int Time in nanoseconds per frame
     */
    private $minFps;

    public function __construct(int $targetFps)
    {
        $this->oldTime = hrtime(true);
        $this->fpsTime = hrtime(true);
        $this->targetFps = $targetFps;
        $this->minFps = 1000000000 / $targetFps;
        $this->adjustedMinFps = $this->minFps;
    }

    /**
     * @param callable|null $runner
     */
    public function run(callable $runner = null)
    {
        $newTime = hrtime(true);

        if ($runner !== null && is_callable($runner)) {
            $runner($this);
        }

        $time_since_last_frame_nanoseconds = $newTime - $this->oldTime;
        $time_since_last_fps_nanoseconds = $newTime - $this->fpsTime;

        if ($time_since_last_frame_nanoseconds < $this->adjustedMinFps) {
            $expected_fps = round((1000000000 - $time_since_last_fps_nanoseconds) / $this->minFps);
            $projected_fps = $this->targetFps - $this->fpsCounter;


            $adjustedFrames = $this->targetFps + ($expected_fps - $this->fpsCounter);
            $adjustedMinFps = round($adjustedFrames > 0 ? 1000000000 / $adjustedFrames : 0);
            $projected_adjusted = $expected_fps - $projected_fps;

            if ($projected_fps > $expected_fps) {
                //-- If we going to lose more than five frames, then set to zero, and skip sleeping
                if ($projected_adjusted < -5) {
                    $adjustedMinFps = 0;
                }

                //-- We came off a higher adjustment to not overshoot FPS, so lets just try to reset!
                if ($adjustedMinFps > $this->minFps) {
                    $adjustedMinFps = $this->minFps;
                }
                //-- We are not going to make target FPS lets sleep less!
                echo "We are going to miss our FPS decreasing to " . number_format($adjustedMinFps) . " from " . number_format($this->adjustedMinFps) . " and expected " . number_format($this->minFps) . "\n";

                $this->adjustedMinFps = $adjustedMinFps;

            } elseif ($projected_adjusted > 1) {
                //-- We are going to overshoot target FPS lets sleep more
                echo "We are going to overshoot our FPS increasing to " . number_format($adjustedMinFps) . " from " . number_format($this->adjustedMinFps) . " and expected " . number_format($this->minFps) . "\n";

                $this->adjustedMinFps = $this->minFps;
            }

            $sleepTime = $this->adjustedMinFps - $time_since_last_frame_nanoseconds;
            if ($sleepTime > 0) {
                time_nanosleep(0, $sleepTime);
            }
            $this->oldTime = hrtime(true);
        }

        $this->fpsCounter++;

        if ($time_since_last_fps_nanoseconds >= 1000000000) {
            $this->fps = $this->fpsCounter;
            $this->fpsCounter = 0;
            $this->fpsTime = hrtime(true);
            //-- Lets reset the adjusted time just in case
            $this->adjustedMinFps = $this->minFps;
        }
    }

    public function shouldExit()
    {
        return !$this->exit;

    }

    /**
     * @return bool
     */
    public function isExit(): bool
    {
        return $this->exit;
    }

    /**
     * @param bool $exit
     */
    public function setExit(bool $exit): void
    {
        $this->exit = $exit;
    }

    public function getFps() {
        if ($this->fps > 0) {
            return $this->fps;
        } else {
            return $this->fpsCounter;
        }
    }

}