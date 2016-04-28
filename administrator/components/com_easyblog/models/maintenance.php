    <?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');


require_once(__DIR__ . '/model.php');

class EasyBlogModelMaintenance extends EasyBlogAdminModel
{
    public $_pagination = null;
    public $_total;

    /**
     * Caches the scripts path and filename.
     * @var array
     */
    private static $scripts = array();

    /**
     * Caches the available versions.
     * @var array
     */
    private static $versions = array();

    public function __construct()
    {
        // parent::__construct('maintenance');

        parent::__construct();

        $mainframe = JFactory::getApplication();

        $limit = ($mainframe->getCfg('list_limit') == 0) ? 5 : $mainframe->getCfg('list_limit');
        $limitstart = JRequest::getVar('limitstart', 0, 'REQUEST');

        // In case limit has been changed, adjust it
        $limitstart = (int) ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }


    private function initScripts()
    {
        if (empty(self::$scripts)) {
            $lib = EB::maintenance();

            $scripts = $lib->getScriptFiles('all');

            foreach ($scripts as $script) {
                $item = new EasyBlogModelMaintenanceScriptItem;

                if ($item->load($script)) {
                    self::$scripts[$item->key] = $item;

                    self::$versions[] = $item->version;
                }
            }

            self::$versions = array_unique(self::$versions);
        }

        return true;
    }

    private function getScripts()
    {
        $this->initScripts();

        return self::$scripts;
    }

    public function getVersions()
    {
        $this->initScripts();

        return self::$versions;
    }

    public function getItems()
    {
        $scripts = $this->getScripts();

        $total = 0;

        $results = array();

        $version = $this->getState('version');

        // Allowed filter
        // version
        // search
        foreach ($scripts as $script) {

            if (!empty($version) && $version !== 'all' && $script->version != $version) {
                continue;
            }

            $results[] = $script;

            $total++;
        }

        $this->_total = $total;
        $this->setState('total', $total);

        // Ordering
        usort($results, array($this, 'sortItems'));

        // var_dump($this->getState('ordering'), $this->getState('direction')); exit;

        $limit = (int) $this->getState('limit');

        if ($limit > 0)
        {
            $this->setState('limit', $limit);

            $limitstart = $this->app->getUserStateFromRequest('limitstart', 0);
            $limitstart = (int) ($limit > 0 ? (floor($limitstart / $limit) * $limit ) : 0);

            $this->setState('limitstart', $limitstart);

            $results = array_slice($results, $limitstart, $limit);
        }

        return $results;
    }

    /**
     * Method to get a pagination object for the events
     *
     * @access public
     * @return integer
     */
    public function getPagination()
    {
        jimport('joomla.html.pagination');
        $this->_pagination  = EB::pagination(  $this->_total, $this->getState('limitstart'), $this->getState('limit') );

        return $this->_pagination;
    }

    private function sortItems($a, $b)
    {
        $ordering = $this->getState('ordering');
        $direction = $this->getState('direction');

        if (empty($ordering) || !isset($a->$ordering) || !isset($b->$ordering) || $a->$ordering == $b->$ordering) {
            return 0;
        }

        $marker = $direction === 'desc' ? -1 : 1;

        $result = $a->$ordering < $b->$ordering ? -$marker : $marker;

        return $result;
    }

    public function getItemByKeys($keys)
    {
        $scripts = $this->getScripts();

        $results = array();

        foreach ($keys as $key) {
            if (isset($scripts[$key])) {
                $results[] = $scripts[$key];
            }
        }

        return $results;
    }

    public function getItemByKey($key)
    {
        // If we are getting by a single key, then we see if cache is loaded
        // If cache is not loaded, we don't initiate it because it is unnecessary for cases of ajax loading 1 single script
        if (!empty(self::$scripts)) {
            $scripts = $this->getItemByKeys(array($key));

            if (count($scripts) < 1) {
                return false;
            }

            return $scripts[0];
        }

        $file = EBLOG_ADMIN_UPDATES . '/' . $key;

        if (!JFile::exists($file)) {
            return false;
        }

        $script = new EasyBlogModelMaintenanceScriptItem($file);

        return $script;
    }
}

class EasyBlogModelMaintenanceScriptItem
{
    public $file;

    public $key;
    public $filename;
    public $version;
    public $classname;
    public $title;
    public $description;

    CONST PREFIX = 'EasyBlogMaintenanceScript';
    CONST BASE = EBLOG_ADMIN_UPDATES;

    public function __construct($file = null)
    {
        if (!empty($file)) {
            $this->load($file);
        }
    }

    public function load($file)
    {
        $this->file = $file;

        if (!JFile::exists($file)) {
            return false;
        }

        require_once($file);

        // $this->key = str_ireplace(self::BASE . '/', '', $file);

        // below is to make compatible with window platform the directory separator
        $tmpFileArr = explode('/', $file);
        $tmpKey = $tmpFileArr[count($tmpFileArr) - 2] . '/' . $tmpFileArr[count($tmpFileArr) - 1];
        $this->key = $tmpKey;

        list($this->version, $this->filename) = explode('/', $this->key);

        $classname = self::PREFIX . str_ireplace('.php', '', $this->filename);

        if (!class_exists($classname)) {
            return false;
        }

        $this->classname = $classname;

        // PHP 5.2 compatibility
        $vars = get_class_vars($classname);

        $this->title = $vars['title'];

        $this->description = $vars['description'];

        return true;
    }

    public function toString()
    {
        return $this->file;
    }

    public function __toString()
    {
        return $this->toString();
    }
}
