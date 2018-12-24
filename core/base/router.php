<?php
/**
 * yato url路由.
 *
 * User: Administrator
 * Date: 2018/12/24
 * version: 1.0
 * link: http://www.glxuexi.com
 */

namespace core\base;


use function Couchbase\defaultDecoder;

class router
{
    private $_queryString = [];
    private $_urlType = 1;
    private $_requestParams = [];
    private $_config = [];

    public function __construct($config)
    {
        $this->_urlType = $config['urlType'];
        $this->_config = $config;
        $this->_queryString = $this->getQueryString();
    }

    public function getQueryString()
    {
        return isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    }

    public function getRequestType()
    {
        return strtoupper(
            isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD']
                : 'GET'
        );
    }

    public function getIsAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public function getServerName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    public function getServerPort()
    {
        return $_SERVER['SERVER_PORT'];
    }

    public function getUrlReferrer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER']
            : null;
    }

    public function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT']
            : null;
    }

    public function getUserHostAddress()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR']
            : '127.0.0.1';
    }

    public function getUserHost()
    {
        return isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : null;
    }

    public function getScriptFile()
    {
        if ($this->_scriptFile !== null) {
            return $this->_scriptFile;
        } else {
            return $this->_scriptFile = realpath($_SERVER['SCRIPT_FILENAME']);
        }
    }

    public function getUrlParam()
    {
        switch ($this->_urlType) {
            case 1:
                $this->queryParams();
                break;
            case 2:
                $this->pathInfoParams();
                break;
            default:
                break;
        }
        return $this->_requestParams;
    }

    public function getPathInfo()
    {
        return isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null;
    }

    public function queryParams()
    {
        $this->_requestParams['c'] = isset($_REQUEST['c']) ? $_REQUEST['c']
            : $this->_config['defaultController'];
        $this->_requestParams['a'] = isset($_REQUEST['a']) ? $_REQUEST['a']
            : $this->_config['defaultAction'];
        $this->_requestParams['m'] = isset($_REQUEST['m']) ? $_REQUEST['m']
            : $this->_config['defaultModule'];
        $this->_requestParams['params'] = $_REQUEST;

    }

    public function pathInfoParams()
    {
        if ($this->getPathInfo()) {
            $pathInfo = explode('/', substr($this->getPathInfo(), 1));
            if (count($pathInfo) > 3) {
                $this->_requestParams['m'] = $pathInfo[0];
                $this->_requestParams['c'] = $pathInfo[1];
                $this->_requestParams['a'] = $pathInfo[2];
            } elseif (count($pathInfo) > 2) {
                $this->_requestParams['m'] = $this->_config['defaultModule'];
                $this->_requestParams['c'] = $pathInfo[0];
                $this->_requestParams['a'] = $pathInfo[1];
            } else {
                $this->_requestParams['m'] = $this->_config['defaultModule'];
                $this->_requestParams['c']
                    = $this->_config['defaultController'];
                $this->_requestParams['a'] = $this->_config['defaultAction'];
            }
        }
    }
}