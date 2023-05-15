<?php

namespace blacksenator\fbvalidateurl;

/**
 * The class provides functions to validate and get the URL structur for
 * FRITZ!Box router
 * Example:
 * $url => [
 *     ['scheme'] => '',   // 'http' or 'https'
 *     ['host']   => **',  // your given IP or 'fritz.box'
 *     ['port']   => ''
 * ]
 *
 * @author Volker Püschel <knuffy@anasco.de>
 * @copyright Volker Püschel 2019 - 2022
 * @license MIT
**/

class FbValidateURL
{
    const HOSTNAME    = 'fritz.box';
    const SECURE_PORTS = ['443', '49443'];
    const OPEN_PORTS   = ['80', '49000'];

    private $url = [];
    private $securePorts = [];
    private $openPorts = [];

    public function __construct()
    {
        $this->setSecurePorts();
        $this->setOpenPorts();
    }

    /**
     * setting the ports decleared as secure
     *
     * @param array $securePorts
     * @return void
     */
    public function setSecurePorts(array $securePorts = null)
    {
        $this->securePorts = $securePorts ?? self::SECURE_PORTS;
    }

    /**
     * setting the ports decleared as open
     *
     * @param array $openPorts
     * @return void
     */
    public function setOpenPorts(array $openPorts = null)
    {
        $this->openPorts = $openPorts ?? self::OPEN_PORTS;
    }

    /**
     * returns the ports decleared as secure
     *
     * @return array $this->securePorts
     */
    public function getSecurePorts()
    {
        return $this->securePorts;
    }

    /**
     * returns the ports decleared as open
     *
     * @return array $this->openPorts
     */
    public function getOpenPorts()
    {
        return $this->openPorts;
    }

    /**
     * get an array of valid URL with all components
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
                $this->isSecurePort($this->url['port']) ? $this->url['scheme'] = 'https' : $this->url['scheme'] = 'http';
            }
        } else {
            if (isset($this->url['port'])) {
                if (($this->url['scheme'] == 'http' && ($this->isSecurePort($this->url['port']))) ||
                    ($this->url['scheme'] == 'https' && (!$this->isSecurePort($this->url['port'])))) {
                    throw new \Exception($errorMessage);
                }
            }
        }

        return $this->url;
    }

    /**
     * returns if a given port is secure
     *
     * @param string $port
     * @return bool
     */
    private function isSecurePort(string $port)
    {
        return in_array($port, $this->securePorts);
    }
}
