<?php


namespace raylib\Helpers;


class AppState
{

    /**
     * @var \raylib\Helpers\StateManager|null
     */
//    protected ?StateManager $stateManager;

    protected string $id;
    /**
     * @var \raylib\Helpers\Application
     */
    protected Application $app;
    protected bool $initialized = false;
    protected bool $enabled = true;
    protected bool $updated = false;
    protected bool $rendered = false;

    public function getId(): string
    {
        return $this->id;
    }

    public function stateAttached(StateManager $stateManager)
    {
//        $this->stateManager = $stateManager;
    }

    public function stateDetach(StateManager $stateManager)
    {
//        $this->stateManager = null;
    }

    public function initialize(StateManager $stateManager, Application $app)
    {
        $this->initialize = true;

        $this->app = $app;

        if ($this->isEnabled()) {
            $this->onEnable();
        }
    }

    public function cleanup()
    {

    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function update(float $tpf)
    {

    }

    public function postRender()
    {

    }

    private function onEnable()
    {

    }

    public function getApplication()
    {
        return $this->app;
    }

    public function getAppStateManager()
    {
        $this->app->getStateManager();
    }

    public function setEnabled(bool $enabled)
    {
        if ($this->enabled == $enabled) {
            return;
        }
        $this->enabled = $enabled;
        if (!$this->isInitialized()) {
            return;
        }
        if ($enabled) {
//            log.log(Level.FINEST, "onEnable():{0}", this);
            $this->onEnable();
        } else {
//            log.log(Level.FINEST, "onDisable():{0}", this);
            $this->onDisable();
        }
    }

    public function isInitialized()
    {
        return $this->initialized;
    }

    public function onDisable()
    {
    }

    public function render()
    {

    }

    public function setRendered(bool $rendered)
    {
        $this->rendered = $rendered;
    }

    public function setUpdated(bool $updated)
    {
        $this->updated = $updated;
    }

    public function isRendered(): bool
    {
        return $this->rendered;
    }

    public function isUpdated(): bool
    {
        return $this->updated;
    }
}