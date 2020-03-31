<?php


namespace raylib\Helpers;


class StateManager
{
    /**
     * List holding the attached app states that are pending
     * initialization.  Once initialized they will be added to
     * the running app states.
     *
     * @var \raylib\Helpers\AppState[]
     */
    public $initializing = [];

    /**
     * Holds the active states once they are initialized.
     *
     * @var \raylib\Helpers\AppState[]
     */
    public $states = [];

    /**
     * List holding the detached app states that are pending
     * cleanup.
     *
     * @var \raylib\Helpers\AppState[]
     */
    public $terminating = [];

    private \raylib\Helpers\Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     *  Returns the Application to which this AppStateManager belongs.
     *
     * @return \raylib\helpers\Application
     */
    public function getApplication(): \raylib\helpers\Application
    {
        return $this->app;
    }

    /**
     * @return \raylib\Helpers\AppState[]
     */
    protected function getInitializing(): array
    {
        return $this->initializing;
    }

    /**
     * @return \raylib\Helpers\AppState[]
     */
    protected function getTerminating(): array
    {
        return $this->terminating;
    }

    /**
     * @return \raylib\Helpers\AppState[]
     */
    protected function getStates(): array
    {
        return $this->states;
    }


    /**
     * Attach a state to the AppStateManager, the same state cannot be attached
     * twice.  Throws an IllegalArgumentException if the state has an ID and that
     * ID has already been associated with another AppState.
     *
     * @param \raylib\Helpers\AppState $state
     *
     * @return bool True if the state was successfully attached, false if the state
     * was already attached.
     *
     * @throws \Exception
     */
    public function attach(AppState $state): bool
    {
        if (isset($this->states[$state->getId()])) {
            throw new \Exception('ID:' . $state->getId()
                . ' is already being used by another state');
        }

        if (!array_search($state, $this->states) && !array_search($state, $this->initializing)) {
            $state->stateAttached($this);
            $this->initializing[] = $state;

            return true;
        } else {
            return false;
        }

    }

    /**
     * Attaches many state to the AppStateManager in a way that is guaranteed
     * that they will all get initialized before any of their updates are run.
     * The same state cannot be attached twice and will be ignored.
     *
     * @param \raylib\Helpers\AppState[] $states
     *
     * @throws \Exception
     */
    public function attachAll(array $states) : void {
        foreach ($states as $state) {
            $this->attach($state);
        }
    }

    /**
     * Detaches the state from the AppStateManager.
     *
     * @param \raylib\Helpers\AppState $state The state to detach
     *
     * @return bool if the state was detached successfully, false
     * if the state was not attached in the first place.
     */
    public function detach(AppState $state) : bool {

        $stateIndex = array_search($state, $this->states);
        if ($stateIndex) {
            $state->stateDetach($this);
            unset($this->states[$stateIndex]);
            $this->terminating[] = $state;

            return true;
        } else {
            return false;
        }

    }

    /**
     * @param \raylib\Helpers\AppState $state
     *
     * @return bool
     */
    public function hasState(AppState $state) : bool {
        return array_search($state, $this->states) !== false || array_search($state, $this->initializing) !== false;
    }

    public function getState(string $id) {
        return $this->states[$id];
    }

    protected function initializePending() : void {
        foreach ($this->initializing as $i => $state) {
            echo $i, " initialized\n";
            $state->initialize($this, $this->app);
            $this->states[$state->getId()] = $state;

            unset($this->initializing[$i]);
        }
    }

    protected function terminatePending() : void {
        /** @var \raylib\Helpers\AppState $state */
        foreach ($this->terminating as $i => $state) {
            $timerKey = sprintf("%s->cleanup", get_class($state));
            if ($this->app->getAppProfiler() != null) {
                $this->app->getAppProfiler()::start($timerKey);
            }
            $state->cleanup();
            if ($this->app->getAppProfiler() != null) {
                $this->app->getAppProfiler()::stop($timerKey);
            }
        }

        $this->terminating = [];
    }

    public function update(float $tpf) {
        $this->terminatePending();

        $this->initializePending();

        foreach ($this->states as $state) {
            if ($state->isEnabled() && !$state->isUpdated()) {
                $timerKey = sprintf("%s->update", get_class($state));
                if ($this->app->getAppProfiler() != null) {
                    $this->app->getAppProfiler()::start($timerKey);
                }
                $state->update($tpf);
                $state->setUpdated(true);
                if ($this->app->getAppProfiler() != null) {
                    $this->app->getAppProfiler()::stop($timerKey);
                }
            }
        }
    }

    public function render()
    {
        foreach ($this->states as $state) {
            if ($state->isEnabled() && !$state->isRendered()) {
                $timerKey = sprintf("%s->render", get_class($state));
                if ($this->app->getAppProfiler() != null) {
                    $this->app->getAppProfiler()::start($timerKey);
                }
                $state->render();
                $state->setRendered(true);
                if ($this->app->getAppProfiler() != null) {
                    $this->app->getAppProfiler()::stop($timerKey);
                }
            }
        }

    }

    public function postRender() {
        foreach ($this->states as $state) {
            if ($state->isEnabled() && !$state->isRendered()) {
                $timerKey = sprintf("%s->postRender", get_class($state));
                if ($this->app->getAppProfiler() != null) {
                    $this->app->getAppProfiler()::start($timerKey);
                }
                $state->postRender();
                if ($this->app->getAppProfiler() != null) {
                    $this->app->getAppProfiler()::stop($timerKey);
                }
            }
        }
    }

    public function cleanup() {
        foreach ($this->states as $state) {
            $timerKey = sprintf("%s->cleanup", get_class($state));
            if ($this->app->getAppProfiler() != null) {
                $this->app->getAppProfiler()::start($timerKey);
            }
            $state->cleanup();
            if ($this->app->getAppProfiler() != null) {
                $this->app->getAppProfiler()::stop($timerKey);
            }
        }
    }

    public function resetUpdates() {
        foreach ($this->states as $state) {
            $state->setUpdated(false);
        }
    }

    public function resetRenders() {
        foreach ($this->states as $state) {
            $state->setRendered(false);
        }
    }

}