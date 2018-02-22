<?php

namespace SmartReplace\SmartReplace;


class Request Extends \Viron\Request{

    /**
     * Parse path of URI
     * @param string $path
     * @return bool
     */
    protected function _parseURI($path) {

        if($path) {

            $frags = explode('/', $path);

            $this->path = $frags;

            $last = array_pop($frags);

            if(strpos($last, '.')) {
                list($last, $this->format) = explode('.', $last);
            }

            if ($last) {
                $this->method = ucfirst(strtolower($last));
            }

            if ($frags) {

                foreach ($frags as $frag) {
                    if ($frag) {

                        if ($this->action) {
                            $this->action.='\\';
                        }
                        $this->action.=ucfirst(strtolower($frag));
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }

}
