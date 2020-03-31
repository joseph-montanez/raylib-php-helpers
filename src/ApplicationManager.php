<?php


namespace raylib\Helpers;


class ApplicationManager
{
    /**
     * @var \raylib\Helpers\Application[] $application
     */
    public $applications = [];

    public function __construct($applications = [])
    {
        $this->attachAll($applications);
    }

    public function update()
    {
        /** @var \raylib\Helpers\Application $application */
        //-- First reset all updates
        foreach ($this->applications as $application) {
            $application->getStateManager()->resetUpdates();
        }

        //-- Now update all
        foreach ($this->applications as $application) {
            $application->update();
        }
    }

    public function render()
    {
        /** @var \raylib\Helpers\Application $application */

        //-- First reset all renders
        foreach ($this->applications as $application) {
            $application->getStateManager()->resetRenders();
        }

        //-- Now render all
        foreach ($this->applications as $application) {
            $application->render();
        }
    }



    /**
     * Attach a application to the ApplicationManager, the same application cannot be attached
     * twice.  Throws an IllegalArgumentException if the application has an ID and that
     * ID has already been associated with another AppState.
     *
     * @param \raylib\Helpers\Application $application
     *
     * @return bool True if the application was successfully attached, false if the application
     * was already attached.
     *
     * @throws \Exception
     */
    public function attach(Application $application): bool
    {
        if (isset($this->applications[$application->getId()])) {
            throw new \Exception('ID:' . $application->getId()
                . ' is already being used by another application');
        }

        if (!array_search($application, $this->applications)) {
            $application->applicationAttached($this);
            $this->applications[$application->getId()] = $application;
            return true;
        } else {
            return false;
        }

    }


    /**
     * Attaches many application to the ApplicationManager in a way that is guaranteed
     * that they will all get initialized before any of their updates are run.
     * The same application cannot be attached twice and will be ignored.
     *
     * @param \raylib\Helpers\Application[] $applications
     *
     * @throws \Exception
     */
    public function attachAll(array $applications) : void {
        foreach ($applications as $application) {
            $this->attach($application);
        }
    }

    /**
     * Detaches the application from the ApplicationManager.
     *
     * @param \raylib\Helpers\Application $application The application to detach
     *
     * @return bool if the application was detached successfully, false
     * if the application was not attached in the first place.
     */
    public function detach(Application $application) : bool {
        $applicationIndex = array_search($application, $this->applications);
        if ($applicationIndex) {
            unset($this->applications[$applicationIndex]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param \raylib\Helpers\Application $application
     *
     * @return bool
     */
    public function hasApplication(Application $application) : bool {
        return array_search($application, $this->applications) !== false;
    }

    public function getApplication(string $id) {
        return $this->applications[$id];
    }
}