<?php<?php

namespace SaschaEnde\T3helpers\Utilities;

use TYPO3\CMS\Core\SingletonInterface;

class Session implements SingletonInterface {

    protected $extension;
    protected $sessiondata = [];

    /**
     * @param $extension
     * @return $this
     */
    public function setExtension($extension) {
        $this->extension = $extension;
        if (!isset($this->sessiondata[$this->extension])) {
            $res = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extension);
            if ($res) {
                $this->sessiondata[$this->extension] = $res;
            } else {
                $this->sessiondata[$this->extension] = [];
            }
        }
        return $this;
    }

    /**
     * @param null $key
     * @return bool|mixed
     */
    public function get($key = null) {
        if($key == null && isset($this->sessiondata[$this->extension])){
            return $this->sessiondata[$this->extension];
        }
        else if (isset($this->sessiondata[$this->extension]) && isset($this->sessiondata[$this->extension][$key])) {
            return $this->sessiondata[$this->extension][$key];
        } else {
            return false;
        }
    }

    /**
     * @param null $key
     * @return bool
     */
    public function remove($key = null){
        if($key == null && isset($this->sessiondata[$this->extension])){
            $this->sessiondata[$this->extension] = [];
            $this->persist();
            return true;
        }
        elseif($this->exists($key)){
            unset($this->sessiondata[$this->extension][$key]);
            $this->persist();
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value) {
        $this->sessiondata[$this->extension][$key] = $value;
        $this->persist();
    }

    /**
     * @param $key
     * @return bool
     */
    public function exists($key) {
        if (isset($this->sessiondata[$this->extension][$key])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Persist changes into the session
     */
    private function persist(){
        $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extension, $this->sessiondata[$this->extension]);
        // say TYPO3 to save the new Session Data
        $GLOBALS["TSFE"]->fe_user->sesData_change = true;
        $GLOBALS['TSFE']->fe_user->storeSessionData();
    }

}
