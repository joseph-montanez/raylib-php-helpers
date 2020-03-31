<?php


namespace raylib\Helpers;


use Ayesh\PHP_Timer\Timer;

class Application
{
    protected $id;
    protected StateManager $stateManager;
    protected bool $pause = false;
    protected float $speed = 1.0;
    protected Timer $prof;
    /**
     * @var bool
     */
    protected bool $enabled = true;

    protected ApplicationManager $applicationManager;

    /**
     * Application constructor.
     *
     * @param string                     $id
     * @param \raylib\Helpers\AppState[] $states
     *
     * @throws \Exception
     */
    public function __construct(string $id, array $states = [])
    {
        $this->id = $id;

        $this->stateManager = new StateManager($this);

        $this->stateManager->attachAll($states);

        $this->prof = new Timer;
    }

    function __destruct()
    {
        $this->stateManager->cleanup();
    }

    /**
     * @return \raylib\Helpers\StateManager
     */
    public function getStateManager(): StateManager
    {
        return $this->stateManager;
    }

    public function update()
    {
        $this->prof::resetAll();

        $timerKey = sprintf("%s", __METHOD__);
        $this->prof::start($timerKey);

        //-- Check if application is enabled, if not skip
        if (!$this->enabled) { $this->prof::stop($timerKey); return; }

        //-- Check if application is enabled, if not skip
        if ($this->pause) { $this->prof::stop($timerKey); return; }

        $tpf = \raylib\Timming::getFrameTime() * $this->speed;
        $this->stateManager->update($tpf);
        $this->onUpdate();

        $this->prof::stop($timerKey);
    }

    public function render()
    {
        $timerKey = sprintf("%s",__METHOD__);
        $this->prof::start($timerKey);

        if (!$this->enabled) { $this->prof::stop($timerKey); return; }

        $this->stateManager->render();
        $this->onRender();
        $this->stateManager->postRender();

        $this->prof::stop($timerKey);
    }

    protected function onUpdate()
    {
    }

    protected function onRender()
    {
    }

    /**
     * @return \Ayesh\PHP_Timer\Timer
     */
    public function getAppProfiler(): Timer
    {
        return $this->prof;
    }

    public function setSpeed(float $speed)
    {
        $this->speed = $speed;
    }

    public function getSpeed(): float
    {
        return $this->speed;
    }

    public function setPause(bool $pause): void
    {
        $this->pause = $pause;
    }

    public function getPause(): bool
    {
        return $this->pause;
    }

    public function setEnable(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getEnable(): bool
    {
        return $this->enabled;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function applicationAttached(ApplicationManager $appMgr)
    {
        $this->applicationManager = $appMgr;
    }

    public function getApplicationManager() : ApplicationManager
    {
        return $this->applicationManager;
    }
}