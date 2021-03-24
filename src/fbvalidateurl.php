<?php

namespace blacksenator\fbvalidateurl;

/**
  * The class provides a function to validate and get the URL
  * structur for FRITZ!Box router
  * Example:
  * $url => [
  *     ['scheme'] => '',   // 'http' or 'https'
  *     ['host']   => **',  // your given IP or 'fritz.box'
  *     ['port']   => ''
  * ]
  *
  * @author Volker Püschel <knuffy@anasco.de>
  * @copyright Volker Püschel 2019 - 2021
  * @license MIT
 **/

class fbvalidateurl
{
    const HOSTNAME = 'fritz.box';

    private $url = [];

    /**
     * get an array of valid URL
     *
     * @param string $url
     * @return array $this->url
     */
    public function getValidURL($url)
    {
        $errorMessage = sprintf('Validation error! %s is not a valid URL!', $url);
        $this->url = parse_url($url);
        if (!isset($this->url['host'])) {
            if (!isset($this->url['path'])) {
                throw new \Exception($errorMessage);
            } else {
                $this->url['path'] = strtolower($this->url['path']);
                if ($this->url['path'] == self::HOSTNAME) {
                    $this->url['host'] = $this->url['path'];
                } else {
                    if (!inet_pton($this->url['path'])) {
                        throw new \Exception($errorMessage);
                    } else {
                        $this->url['host'] = $this->url['path'];
                    }
                }
            }
        } else {
            if ($this->url['host'] != self::HOSTNAME && !inet_pton($this->url['host'])) {
                throw new \Exception($errorMessage);
            }
        }
        if (!isset($this->url['scheme'])) {
            if (!isset($this->url['port'])) {
                $this->url['scheme'] = 'http';                  // default http
            } else {
                $this->url['port'] == '443' ? $this->url['scheme'] = 'https' : $this->url['scheme'] = 'http';
            }
        } else {
            if (isset($this->url['port'])) {
                if (($this->url['scheme'] == 'http' &&
                    ($this->url['port'] == '443' || $this->url['port'] == '49443')) ||
                    ($this->url['scheme'] == 'https' &&
                    ($this->url['port'] == '80' || $this->url['port'] == '49000')
                    )) {
                    throw new \Exception($errorMessage);
                }
            }
        }

        return $this->url;
    }
}
