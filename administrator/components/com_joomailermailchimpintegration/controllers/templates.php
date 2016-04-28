<?php
/**
* Copyright (C) 2015  freakedout (www.freakedout.de)
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/

// no direct access
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/fileuploader.php');

class joomailermailchimpintegrationControllerTemplates extends joomailermailchimpintegrationController {

    public function __construct() {
        parent::__construct();

        // Register Extra tasks
        $this->registerTask('add', 'upload');
        $this->registerTask('start_upload', 'start_upload');
    }

    public function edit() {
        JRequest::setVar('view', 'templates');
        JRequest::setVar('layout', 'edit');
        JRequest::setVar('hidemainmenu', 1);

        parent::display();
    }

    public function upload() {
        JRequest::setVar('view', 'templates');
        JRequest::setVar('layout', 'upload');
        JRequest::setVar('hidemainmenu', 1);

        parent::display();
    }

    public function startUpload() {
        $file 	 = JRequest::getVar('Filedata', '', 'files', 'array');
        $folder	 = JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/templates/';
        $format	 = JRequest::getVar('format', 'html', '', 'cmd');
        $return	 = JRequest::getVar('return-url', null, 'post', 'base64');
        $err	 = null;
        $msgType = 'message';

        // Set FTP credentials, if given
        jimport('joomla.client.helper');
        JClientHelper::setCredentialsFromRequest('ftp');

        // Make the filename safe
        $file['name'] = str_replace(' ', '_', JFile::makeSafe($file['name']));

        if (JFolder::exists($folder . JFile::stripExt($file['name']))) {
            $msg = JText::_('JM_TEMPLATE_EXISTS');
            $msgType = 'error';
        } else if ($file['name'] != '') {
            $filepath = JPath::clean($folder . '/' . strtolower($file['name']));

            if (!$this->canUpload($file, $err)) {
                if ($format == 'json') {
                    jimport('joomla.error.log');
                    $log = JLog::getInstance('upload.error.php');
                    $log->addEntry(array('comment' => 'Invalid: ' . $filepath . ': ' . $err));
                    header('HTTP/1.0 415 Unsupported Media Type');
                    jexit('Error. Unsupported Media Type!');
                } else {
                    JError::raiseNotice(100, JText::_($err));
                    if ($return) {
                        $this->app->redirect(base64_decode($return));
                    }
                    return;
                }
            }

            if (JFile::exists($filepath)) {
                JFile::delete($filepath);
            }

            if (!JFile::upload($file['tmp_name'], $filepath)) {
                if ($format == 'json') {
                    jimport('joomla.error.log');
                    $log = JLog::getInstance('upload.error.php');
                    $log->addEntry(array('comment' => 'Cannot upload: ' . $filepath));
                    header('HTTP/1.0 400 Bad Request');
                    jexit('Error. Unable to upload file');
                } else {
                    JError::raiseWarning(100, JText::_('Error. Unable to upload file'));
                    if ($return) {
                        $this->app->redirect(base64_decode($return));
                    }
                    return;
                }
            } else {
                if ($format == 'json') {
                    jimport('joomla.error.log');
                    $log = JLog::getInstance();
                    $log->addEntry(array('comment' => $folder));
                    jexit(JText::_('JM_UPLOAD_COMPLETE'));
                } else {
                    if ($this->unzip($folder, strtolower($file['name']))) {
                        $msg = JText::_('JM_UPLOAD_COMPLETE');
                    } else {
                        $msg = JText::_('Error. Unable to upload file');
                        $msgType = 'error';
                    }
                }
            }
        } else {
            $this->app->enqueueMessage('Invalid file name', 'error');
            $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=templates');
        }

        $this->app->enqueueMessage($msg, $msgType);
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=templates');
    }

    private function canUpload($file, &$err) {
        $params = JComponentHelper::getParams('com_media');

        if (empty($file['name'])) {
            $err = 'JM_PLEASE_SELECT_A_FILE_TO_UPLOAD';
            return false;
        }

        if ($file['name'] !== JFile::makesafe($file['name'])) {
            $err = 'JM_WARNFILENAME';
            return false;
        }

        $format = strtolower(JFile::getExt($file['name']));

        $allowable = array('zip', 'gzip', 'gz', 'tar', 'tgz');
        $ignored = explode(',', $params->get('params.ignore_extensions'));
        if (!in_array($format, $allowable) && !in_array($format,$ignored)) {
            $err = 'JM_WARNFILETYPE';
            return false;
        }

        $maxSize = (int) $params->get('params.upload_maxsize', 0);
        if ($maxSize > 0 && (int) $file['size'] > $maxSize) {
            $err = 'JM_WARNFILETOOLARGE';
            return false;
        }

        $user = JFactory::getUser();
        $imginfo = null;
        if ($params->get('params.restrict_uploads', 1)) {
            $images = explode(',', $params->get('params.image_extensions'));
            if (in_array($format, $images)) { // if its an image run it through getimagesize
                if (($imginfo = getimagesize($file['tmp_name'])) === FALSE) {
                    $err = 'WARNINVALIDIMG';
                    return false;
                }
            } else if (!in_array($format, $ignored)) {
                // if its not an image...and we're not ignoring it
                $allowed_mime = explode(',', $params->get('params.upload_mime'));
                $illegal_mime = explode(',', $params->get('params.upload_mime_illegal'));
                if (function_exists('finfo_open') && $params->get('params.check_mime', 1)) {
                    // We have fileinfo
                    $finfo = finfo_open(FILEINFO_MIME);
                    $type = finfo_file($finfo, $file['tmp_name']);
                    if (strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
                        $err = 'WARNINVALIDMIME';
                        return false;
                    }
                    finfo_close($finfo);
                } else if (function_exists('mime_content_type') && $params->get('params.check_mime', 1)) {
                    // we have mime magic
                    $type = mime_content_type($file['tmp_name']);
                    if (strlen($type) && !in_array($type, $allowed_mime) && in_array($type, $illegal_mime)) {
                        $err = 'WARNINVALIDMIME';
                        return false;
                    }
                } else if (!$user->authorise('login', 'administrator')) {
                    $err = 'WARNNOTADMIN';
                    return false;
                }
            }
        }

        $xss_check = JFile::read($file['tmp_name'], false, 256);
        $html_tags = array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
        foreach($html_tags as $tag) {
            // A tag is '<tagname ', so we need to add < and a space or '<tagname>'
            if (stristr($xss_check, '<'.$tag.' ') || stristr($xss_check, '<'.$tag.'>')) {
                $err = 'WARNIEXSS';
                return false;
            }
        }

        return true;
    }

    private function unzip($folder, $path) {
        // Set FTP credentials, if given
        jimport('joomla.client.helper');
        JClientHelper::setCredentialsFromRequest('ftp');
        $jFilterInput = new JFilterInput();
        if ($path !== $jFilterInput->clean($path, 'path')) {
            JError::raiseWarning(100, JText::_('JM_UNABLE_TO_EXTRACT') . htmlspecialchars($path, ENT_COMPAT,'UTF-8') .
                ' ' . JText::_('WARNDIRNAME'));
        }

        $fullPath = JPath::clean($folder . '/' . $path);

        if (is_file($fullPath)) {
            $ext = JFile::getExt(strtolower($fullPath));
            $pathdir = $fullPath;
            if ($ext != 'gz') {
                $pathdir = str_replace(".".$ext, "",$pathdir);
            } else {
                $pathdir = str_replace(".".$ext, "",$pathdir);
                $pathdir = str_replace(".tar", "",$pathdir);
            }

            jimport('joomla.filesystem.*');
            jimport('joomla.filesystem.archive');
            JFolder::create($pathdir);
            $blankPageContent = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
            JFile::write($pathdir . '/index.html', $blankPageContent);
            if (JArchive::extract($fullPath, $pathdir)) {
                JFile::delete($folder . '/' . $path);
            }
        } else if (is_dir($fullPath)) {
            JError::raiseWarning(100, JText::_('JM_UNABLE_TO_EXTRACT') . $fullPath . ' ' . JText::_('WARNFILETYPE'));
            JFile::delete($folder . '/' . $path);
        }

        return true;
    }

    public function remove() {
        // Set FTP credentials, if given
        jimport('joomla.client.helper');
        JClientHelper::setCredentialsFromRequest('ftp');

        // Get some data from the request
        $path    = JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/templates/';
        $folders = JRequest::getVar('template', array(), '', 'array');

        // Initialize variables
        $ret = true;

        foreach ($folders as $folder) {

            // delete zip file
            @chmod($path.'/'.$folder.'.zip', 0777);
            @unlink($path.'/'.$folder.'.zip');

            // delete template folder with all contents
            $fullPath = JPath::clean($path . '/' . $folder);

            $files = JFolder::files($fullPath, '.', true);
            foreach ($files as $file) {
                JFile::delete($fullPath.'/'.$file);
            }

            JFolder::delete($fullPath);
        }

        $this->app->enqueueMessage(JText::_('JM_TEMPLATES_DELETED'));
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=templates');
    }

    public function download() {
        // turn off all error reporting to prevent errors showing in download file
        error_reporting(0);

        jimport('joomla.filesystem.archive');
        jimport('joomla.filesystem.archive.zip');
        jimport('joomla.application.web');
        JApplicationWeb::clearHeaders();

        $path = JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/templates/';
        $folder = JRequest::getVar('template', '', '', 'string');
        $fullPath = JPath::clean($path . '/' . $folder);
        $files = JFolder::files($fullPath, '.', false, false);
        $archive = $path . $folder . '.zip';

        $filesData = array();
        for ($i = 0; $i < count($files); $i++) {
            $filesData[$i]['name'] = $files[$i];
            $filesData[$i]['data'] = file_get_contents($fullPath . '/' . $files[$i]);
        }

        // delete file if it already exists
        @chmod($path . '/' . $folder . '.zip', 0777);
        @unlink($path . '/' . $folder . '.zip');

        $JArchiveZip = JArchive::getAdapter('zip');
        $JArchiveZip->create($archive, $filesData);

        // push download
        ob_end_clean();
        ob_start();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"{$folder}.zip\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($archive));
        readfile($archive);
        ob_end_flush();
    }

    public function cancel() {
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=templates');
    }

    public function reloadPalettes() {
        $hex = JRequest::getVar('hex', '', 'POST', 'string');
        $keywords = JRequest::getVar('keywords', '', 'POST', 'string');
        $showNames = JRequest::getVar('showNames', true, 'POST');
        $float = JRequest::getVar('float', false, 'POST');
        $scope = JRequest::getVar('scope', 'templates', 'POST');

        $model = $this->getModel('templates');
        $newPalettes = $model->getPalettes($hex, $keywords);

        $response = array();
        $response['html'] = '';
        $response['js'] = '';
        $i = 0;
        foreach ($newPalettes as $color) {
            foreach ($color as $c) {
                $response['js'] .= 'colorsets[' . $i . '] = [];';
                $response['html'] .= '<div class="color_list" style="margin-bottom: 3px;">';

                if ($showNames) {
                    $response['html'] .= $c->title . '<br />';
                }

                $response['html'] .= '<div class="color_samples" style="display:inline-block;width:125px;">';
                $response['html'] .=  '<a href="javascript:joomlamailerJS.' . $scope . '.applyPalette(' . $i . ');" id="apply' . $i . '" title="' . JText::_('select') . '">';
                $x = 0;
                foreach ($c->colors as $cc) {
                    $response['html'] .= '<div style="background:#' . $cc . ' none repeat scroll 0 0 !important; width: 25px; height: 10px; float: left;"></div>';
                    $response['js'] .= 'colorsets[' . $i . '][' . $x . '] = "#' . $cc . '";';
                    $x++;
                }
                $response['html'] .= '</a></div>';

                $response['html'] .= '<a href="' . $c->url . '" target="_blank" class="ColorSetInfo" style="margin-left:10px;position:relative;top:-2px;text-decoration:underline;">' . JText::_('JM_DETAILS') . '</a>';

                $response['html'] .= '<div class="clr"></div></div>';
                if (!$float) {
                    $response['html'] .= '<div class="clr"></div>';
                }
            }
            $i++;
        }

        echo json_encode($response);
    }

    public function uploadLogo() {
        $template = JRequest::getVar('name', false, '', 'string');
        $uploadPath = JPATH_SITE . '/tmp/';
        if ($template && $template != 'undefined') {
             $uploadPath .= $template . '/';
        }
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
        // max file size in bytes
        $sizeLimit = 10 * 1024 * 1024;

        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($uploadPath, true);
        // to pass data through iframe you will need to encode all html tags
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
    }

    public function save() {
        $template = strtolower(JRequest::getVar('template', false, 'post', 'string'));
        $template = preg_replace('/^[^a-z0-9_-]$/', '', str_replace(' ', '_', $template));
        if (!$template) {
            $this->app->enqueueMessage(JText::_('JM_INVALID_TEMPLATE_NAME_SUPPLIED'), 'error');
            $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=templates');
        }

        $templateOld = JRequest::getVar('templateOld', false, 'post', 'string');
        $columns = JRequest::getVar('columns', false, 'post', 'string');

        if ($template != $templateOld){
            $tmpName = 'tmp_' . rand(100000, 999999);
        } else {
            $tmpName = $template;
        }

        $src = JPATH_SITE . '/tmp/' . $templateOld . '/';
        $dest = JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/templates/' . $tmpName;
        $templatesPath = JURI::root() . 'administrator/components/com_joomailermailchimpintegration/templates';

        $content = JRequest::getVar('templateContent', false, 'post', 'string', JREQUEST_ALLOWRAW);
        $content = str_replace('%7E', '~', $content);
        $content = '<html>' . $content . '</html>';

        $metaData = "<meta http-Equiv=\"Cache-Control\" Content=\"no-cache\">\n<meta http-Equiv=\"Pragma\" Content=\"no-cache\">\n<meta http-Equiv=\"Expires\" Content=\"0\">";
        $content = str_ireplace($metaData, '', $content);
        $content = str_ireplace(' title="click to edit"', '', $content);

        $content = str_ireplace(JURI::root() . 'tmp/' . $templateOld . '/', '', $content);
        $content = str_replace(array("&lt;", "&gt;", "%3C", "%3E", "%7C"), array('<', '>', '<', '>', '|'), $content);
        $content = preg_replace('#<head>(.*)</head>#i', '', $content);

        $msgType = 'message';
        // remove previous template if exists (user decided to replace existing)
        if (JFolder::exists($dest)){
            JFolder::delete($dest);
        }
        if (JFolder::copy($src, $dest, '', true)) {
            JFile::write($dest . '/template.html', $content);

            if (JFile::exists($dest . '/l.txt')){
                JFile::delete($dest . '/l.txt');
            }
            if (JFile::exists($dest . '/r.txt')){
                JFile::delete($dest . '/r.txt');
            }

            if ($columns){
                $content = md5('random content');
                JFile::write($dest . '/' . $columns . '.txt', $content);
            }

            if ($template != $templateOld) {
                $oldName = JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/templates/' . $tmpName;
                $newName = JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/templates/' . $template;
                JFolder::move($oldName, $newName);
            }
            JFolder::delete($src);

            $msg = JText::_('JM_TEMPLATE_SAVED');
        } else {
            $msg = JText::_('JM_ERROR');
            $msgType = 'error';
        }

        $this->app->enqueueMessage($msg, $msgType);
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=templates');
    }

    public function updatePosition() {
        $template = JRequest::getVar('template', '', 'post', 'string');
        $template = '<html>' . rawurldecode($template) . '</html>';
        $insertHtml = html_entity_decode(rawurldecode(JRequest::getVar('insertHtml', '', 'post', 'string')));
        $path = base64_decode(JRequest::getVar('path', '', 'post', 'string'));
        $position = str_replace('.', '', JRequest::getVar('position', '', 'post', 'string'));

        $doc = new DOMDocument();
        //	$doc->formatOutput = true;
        $doc->loadHTML($template);
        $xpath = new DOMXpath($doc);
        $nodes = $xpath->query("//*[@class='$position']");
        foreach($nodes as $node) {
            $node->nodeValue = $insertHtml;
        }
        $template = html_entity_decode($doc->saveHTML());

        if ($template) {
            if (JFile::exists($path)){
                JFile::delete($path);
            }

            $success = JFile::write($path, $template);
        } else {
            $success = false;
        }

        $result['success'] = $success;

        echo json_encode($result);
    }

    public function checkIfTemplateExists() {
        jimport('joomla.filesystem.folder');
        $template = JRequest::getVar('template', '', 'post', 'string');
        echo (JFolder::exists(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/templates/' . $template)) ? 1 : 0;
    }

}
