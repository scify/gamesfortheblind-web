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

class joomailermailchimpintegrationControllerCreate extends joomailermailchimpintegrationController {

    protected $db;

    public function __construct(){
        parent::__construct();
        $this->registerTask('add' , 'send');

        $this->db = JFactory::getDBO();
    }

    public function preview() {
        jimport('joomla.filesystem.file');

        // plugin support
        JPluginHelper::importPlugin('joomlamailer');
        $dispatcher = JDispatcher::getInstance();

        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $error = false;

        $response = array();
        $response['msg'] = '';
        $templateFolder = JRequest::getVar('template');
        $absPath = '$1="' . JURI::root() . '$2$3';
        $imagepath = '$1="' . JURI::root() . 'administrator/components/com_joomailermailchimpintegration/templates/' . $templateFolder . '/$2$3';

        $campaignNameEsc = JApplication::stringURLSafe(urldecode(JRequest::getVar('campaignName')));
        $subject = urldecode(JRequest::getVar('subject'));
        if (get_magic_quotes_gpc()) $subject = stripslashes($subject);
        $introText = urldecode(JRequest::getVar('intro'));
        if (get_magic_quotes_gpc()) $introText = stripslashes($introText);

        if (JRequest::getVar('text_only')) {
            $template = urldecode(JRequest::getVar('text_only_content'));

            // create google analytics tracking links
            if (JRequest::getVar('gaEnabled')) {
                $ga = 'utm_source=' . JRequest::getVar('gaSource') . '&utm_medium=' . JRequest::getVar('gaMedium') . '&utm_campaign=' . JRequest::getVar('gaName');
                $excludedURLs = urldecode(JRequest::getVar('gaExcluded'));
                $excludedURLs = explode("\n", $excludedURLs);
                for ($i = 0; $i < count($excludedURLs); $i++) {
                    $excludedURLs[$i] = trim($excludedURLs[$i]);
                }
                $excludedURLs[] = '*|UNSUB|*';

                $regex = '#http(s*)://(.*?)(\s|\n|\r)#i';
                preg_match_all($regex, $template, $templateLinks, PREG_PATTERN_ORDER);

                if (isset($templateLinks[0])) {
                    foreach($templateLinks[0] as $link) {
                        $glue = (strstr($link, '?'))? $glue = '&' : $glue = '?';
                        $oldHref = substr($link, 0, -1);
                        $addGA = true;
                        foreach($excludedURLs as $ex){
                            if (stristr($link,$ex)) {
                                $addGA = false;
                            }
                        }
                        if ($addGA) {
                            $link = str_replace(array("\s","\n","\r"," ",'%'), array('','','','', '\%'), $link);
                            $template = preg_replace('%' . $link . '(\s|\n|\r)%i', $oldHref . $glue . $ga . '$1', $template);
                        }
                    }
                }
            }

            $template = '<br /><textarea style="width: 98%;height: 500px;padding: 10px;cursor:default;" readonly="readonly">' . $template . '</textarea>';
            $response['html'] = $template;
        } else {
            // display popular articles?
            $popularCheckbox = JRequest::getVar('popular');
            $populararticlesAmount = (int)JRequest::getVar('populararticlesAmount');
            $popularEx = JRequest::getVar('popEx');
            $popularIn = JRequest::getVar('popIn');
            if (isset($k2_installed) && $k2_installed) {
                // include K2 in populars?
                $popularK2Checkbox = JRequest::getVar('populark2');
                $popularK2Ex = JRequest::getVar('popk2Ex');
                $popularK2In = JRequest::getVar('popk2In');
                // only K2 articles in populars?
                $popularK2Only = JRequest::getVar('populark2_only');
            } else {
                $popularK2Checkbox = false;
            }

            //$facebookShareIt = $_POST['facebookShare'];
            $facebookShareIt = 0;

            // open the template file
            $filename = JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/templates/' . $templateFolder . '/template.html';
            $template = JFile::read($filename, false, filesize($filename));
            if (!$template) {
                $response['html'] = '<div style="border: 2px solid #ff0000; margin:15px 0 5px;padding:10px 15px 12px;">' .
                    '<img src="' . JURI::root() . 'media/com_joomailermailchimpintegration/images/warning.png" align="left"/>' .
                    '<div style="padding-left: 45px; line-height: 28px; font-size: 14px;">' .
                        JText::_('JM_TEMPLATE_ERROR') .
                    '</div></div>';
            } else {
                // convert relative to absolute paths
                $template = preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|[.]|\+)[^"]*"))#i', $imagepath, $template);

                // loop through plugins to insert content
                $content = '';
                $componentsPostData = JRequest::getVar('componentsPostData');
                $postData = JRequest::getVar('postData');
                $tableOfContentType = ($postData['table_of_content_type'] === 'true');
                $article_titles = array();

                foreach (JRequest::getVar('includeComponents') as $includeComponent) {
                    $cpd = (isset($componentsPostData[$includeComponent])) ? $componentsPostData[$includeComponent] : array();
                    $pluginResponse = $dispatcher->trigger('insert_' . $includeComponent, array($template, $templateFolder, JRequest::getVar('includeComponentsOptions'), $cpd, $tableOfContentType));

                    $template = $pluginResponse[0]['template'];
                    if (isset($pluginResponse[0]['article_titles'])) {
                        $article_titles = array_merge($article_titles, $pluginResponse[0]['article_titles']);
                    }
                    if (isset($pluginResponse[0]['msg'])) {
                        $response['msg'] .= $pluginResponse[0]['msg'];
                    }
                }

                // insert social icons
                $socialIcons = JRequest::getVar('socialIcons', array());
                if (count($socialIcons)) {
                    foreach($socialIcons as $key => $value) {
                        $pluginResponse = $dispatcher->trigger('insert_' . $key, array($value, $template));
                        $template = $pluginResponse[0];
                    }
                }

                // popular articles
                $regex = '!<#populararticles#[^>]*>(.*)<#/populararticles#>!is';
                preg_match($regex, $template, $populararticles);
                if (isset($populararticles[0])) {
                    $populararticles = $populararticles[0];
                } else {
                    $populararticles = '';
                    if ($popularCheckbox) {
                        $response['msg'] .= JTEXT::_('Error') . ': ' . JTEXT::_('JM_NO_POPULAR_CONTAINER') . '<br />';
                    }
                }
                $regex = '!<#popular_repeater#[^>]*>(.*)<#/popular_repeater#>!is';
                preg_match($regex, $template, $popular_repeater);
                if (isset($popular_repeater[0])) {
                    $popular_repeater = $popular_repeater[0];
                } else {
                    $popular_repeater = '';
                }


                // remove tiny mce stuff like mce_src="..."
                $template = preg_replace('(mce_style=".*?")', '', $template);
                $template = preg_replace('(mce_src=".*?")',   '', $template);
                $template = preg_replace('(mce_href=".*?")',  '', $template);
                $template = preg_replace('(mce_bogus=".*?")', '', $template);
                // convert relative to absolute paths
                $template = preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|[.]|\+)[^"]*"))#i', $absPath, $template);

                // create list of popular articles
                $where = '';
                $wEx = array();
                $wIn = array();
                $wCore = array();
                if ($popularCheckbox){
                    if (isset($popularEx[0])){
                        foreach($popularEx as $p){
                            $wEx[] = ' c.catid != '.$p;
                        }
                        $wCore[] = (count($wEx) ? ' AND (' . implode(' AND ', $wEx) . ')' : '');
                    }
                    if (isset($popularIn[0])){
                        foreach($popularIn as $p){
                            $wIn[] = ' c.catid = '.$p;
                        }
                        $wCore[] = (count($wIn) ? ' AND (' . implode(' OR ', $wIn) . ')' : '');
                    }
                    $where = implode('', $wCore);
                }

                $whereK2 = '';
                $wEx = array();
                $wIn = array();
                $wK2 = array();
                if ($popularK2Checkbox){
                    if (isset($popularK2Ex[0])){
                        foreach($popularK2Ex as $p){
                            $wEx[] = ' k.catid != '.$p;
                        }
                        $wK2[] = (count($wEx) ? ' AND (' . implode(' AND ', $wEx) . ')' : '');
                    }
                    if (isset($popularK2In[0])){
                        foreach($popularK2In as $p){
                            $wIn[] = ' k.catid = '.$p;
                        }
                        $wK2[] = (count($wIn) ? ' AND (' . implode(' OR ', $wIn) . ')' : '');
                    }
                }
                $whereK2 = implode('', $wK2);

                if ($popularCheckbox && !$popularK2Checkbox){
                    $query = 'SELECT c.id, c.title, c.hits FROM #__content as c
                    WHERE (c.state = 1 OR c.state = -2)
                    AND c.hits != 0
                    '.$where.'
                    ORDER BY c.hits DESC
                    LIMIT 0 , '.$populararticlesAmount;
                } else if ($popularCheckbox && $popularK2Checkbox && !$popularK2Only) {
                    $query = 'SELECT c.id, c.title, c.hits
                    FROM #__content as c
                    WHERE (c.state = 1 OR c.state = -2)
                    AND c.hits != 0
                    '.$where.'
                    UNION ALL SELECT k.id, k.title, k.hits
                    FROM #__k2_items as k
                    WHERE k.published = 1
                    AND k.hits != 0
                    '.$whereK2.'
                    ORDER BY hits DESC
                    LIMIT 0 , '.$populararticlesAmount;
                } else if ($popularCheckbox && $popularK2Checkbox && $popularK2Only)  {
                    $query = 'SELECT k.id, k.title, k.hits
                    FROM #__k2_items as k
                    WHERE k.published = 1
                    AND k.hits != 0
                    '.$whereK2.'
                    ORDER BY k.hits DESC
                    LIMIT 0 , '.$populararticlesAmount;
                }

                $popularlist = '';
                if ($popularCheckbox || $popularK2Checkbox) {
                    $this->db->setQuery($query);
                    $popular = $this->db->loadObjectList();
                    $i=0;
                    if ($popular) {
                        foreach ($popular as $pop) {
                            $i++;
                            $core = false;
                            if ($i>5) { break; }

                            $query = 'SELECT title FROM #__content WHERE title = "'.$pop->title.'"';
                            $this->db->setQuery($query);
                            $core = $this->db->loadResult();
                            if ($core) {
                                $url = '<a href="'.JURI::root().'index.php?option=com_content&view=article&id='.$pop->id.'">'.$pop->title.'</a>';
                            } else {
                                $url = '<a href="'.JURI::root().'index.php?option=com_k2&view=item&id='.$pop->id.'">'.$pop->title.'</a>';
                            }
                            $popularlist .= str_ireplace('<#popular_title#>', $url, $popular_repeater);
                        }
                    }
                }
                $popularlist = preg_replace('!<#popular_repeater#[^>]*>(.*)<#/popular_repeater#>!is', $popularlist, $populararticles);
                $toReplace  = array('<#populararticles#>', '<#/populararticles#>', '<#popular_repeater#>', '<#/popular_repeater#>');
                $popularlist = str_ireplace($toReplace, '', $popularlist);

                // modify paths of intro-text
                // remove tiny mce stuff like mce_src="..."
                $introText = preg_replace('(mce_style=".*?")', '', $introText);
                $introText = preg_replace('(mce_src=".*?")',   '', $introText);
                $introText = preg_replace('(mce_href=".*?")',  '', $introText);
                $introText = preg_replace('(mce_bogus=".*?")', '', $introText);
                // convert relative to absolute paths
                $introText = preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|[.]|\+)[^"]*"))#i', $absPath, $introText);
                // end paths intro-text

                // create absolute image paths
                $imagepath = ' src="' . JURI::root() . 'administrator/components/com_joomailermailchimpintegration/templates/' . $templateFolder . '/';
                $template  = preg_replace('#(src)="([^:"]*)("|(?:(?:%20|\s|[.]|\+)[^"]*"))#i', $imagepath.'$2$3', $template);
                $imagepath = " url('" . JURI::root() . "administrator/components/com_joomailermailchimpintegration/templates/" . $templateFolder . '/';
                $template  = preg_replace('#(\s*)url?\([\'"]?[../]*[\'"]?#i', $imagepath, $template);
                $imagepath = ' background="' . JURI::root() . 'administrator/components/com_joomailermailchimpintegration/templates/' . $templateFolder . '/';
                $template  = preg_replace('#(\s*)background=[\'"]?[../]*[\'"]?#i', $imagepath, $template);

                // insert sidebar editor content
                $sidebarElements = JRequest::getVar('sidebarElements', array());
                foreach($sidebarElements as $sidebarElement){
                    $pluginResponse = $dispatcher->trigger('insert_' . $sidebarElement, array(JRequest::getVar('postData'), $template, $article_titles));
                    if (isset($pluginResponse[0])){ $template = $pluginResponse[0]; }
                }

                // insert page title and intro-text
                $template = str_ireplace('<#subject#>', $subject, $template);
                $template = str_ireplace('<#intro_content#>', $introText, $template);

                //insert popular articles
                if ($popularCheckbox){
                    $popularlist = str_ireplace('$' , '\$', $popularlist);
                    $template = preg_replace('!<#populararticles#[^>]*>(.*?)<#/populararticles#>!is', $popularlist, $template);
                } else {
                    $template = preg_replace('!<#populararticles#[^>]*>(.*?)<#/populararticles#>!is', '', $template);
                }

                // un-escape dollars
                $template = str_ireplace('\$', '$', $template);

                // remove unused placeholders
                $template = preg_replace('!<#([^#]+)#>.*?<#/\1#>!s', '', $template);
                $template = preg_replace('/<#[^#]+#>/', '', $template);

                // create google analytics tracking links
                if (JRequest::getVar('gaEnabled')) {
                    $ga = 'utm_source=' . JRequest::getVar('gaSource') . '&utm_medium=' . JRequest::getVar('gaMedium') .
                        '&utm_campaign=' . JRequest::getVar('gaName') . '"';
                    $excludedURLs = urldecode(JRequest::getVar('gaExcluded'));
                    $excludedURLs = explode("\n", $excludedURLs);
                    for($i=0;$i<count($excludedURLs);$i++){
                        $excludedURLs[$i] = trim($excludedURLs[$i]);
                    }
                    $excludedURLs[] = '*|UNSUB|*';

                    $regex = '#<a(.*?)>(.*?)</a>#i';
                    preg_match_all($regex, $template, $templateLinks, PREG_PATTERN_ORDER);

                    if (isset($templateLinks[0])) {
                        foreach($templateLinks[0] as $link){
                            if (!strstr($link, 'javascript')){
                                preg_match_all('#((href)="(?!\.css)[^"]+)"#i' , $link, $oldLink, PREG_PATTERN_ORDER);
                                if (isset($oldLink[0][0])){
                                    $glue = (strstr($oldLink[0][0], '?'))? $glue = '&' : $glue = '?';
                                    $oldHref = substr($oldLink[0][0], 0, -1);
                                    $addGA = true;
                                    foreach ($excludedURLs as $ex){
                                        if (stristr($oldHref, $ex)) {
                                            $addGA = false;
                                        }
                                    }
                                    if ($addGA) {
                                        $newLink = preg_replace('#((href)="(?!\.css)[^"]+)"#i', $oldHref.$glue.$ga.'"', $link);
                                        $template = str_ireplace($oldLink[0][0], $oldHref.$glue.$ga.'"', $template);
                                    }
                                }
                            }
                        }
                    }
                }

                // prevent preview from being cached
                $metaData = '<meta http-Equiv="Cache-Control" Content="no-cache">
                    <meta http-Equiv="Pragma" Content="no-cache">
                    <meta http-Equiv="Expires" Content="0">
                    <script type="text/javascript" src="' . JURI::root() . 'media/com_joomailermailchimpintegration/backend/js/jquery.min.js"></script>
                    <script type="text/javascript">
                    var tmplUrl = "' . JURI::root() . 'tmp/";
                    jQuery(document).ready(function() {
                    jQuery("a").click(function(){
                    link = jQuery(this).attr("href").replace(tmplUrl, "");
                    alert(link);
                    void(0);
                    return false;
                    });
                    });
                    </script>';
                if (!stristr($template, "<head>")){
                    $template = str_ireplace('<html>', '<html><head>'.$metaData.'</head>', $template);
                } else {
                    $template = str_ireplace('</head>', $metaData.'</head>', $template);
                }
            }

            // create output
            if (!$error){
                $filename = JPATH_SITE . '/tmp/' . $campaignNameEsc . '.html';
                if (JFile::exists($filename)) {
                    JFile::delete($filename);
                }

                if (JFile::write($filename, $template)) {
                    $htmlFile = JURI::root() . 'tmp/' . $campaignNameEsc . '.html';
                    $template = '<iframe src="' . $htmlFile . '" width="100%" height="800" name="previewIframe" id="previewIframe"></iframe>';
                    $response['html'] = $template;
                } else {
                    $response['html'] = '<div style="border: 2px solid #ff0000; margin:15px 0 5px;padding:10px 15px 12px;">' .
                        '<img src="' . JURI::root() . 'media/com_joomailermailchimpintegration/backend/images/warning.png" align="left"/>' .
                        '<div style="padding-left: 45px; line-height: 28px; font-size: 14px;">' .
                            JText::sprintf('JM_PERMISSIONS_ERROR_GLOBAL', $params->get('params.archiveDir', '/administrator/components/com_joomailermailchimpintegration/archive')) .
                        '</div></div>';
                }
            }
        }

        // return AJAX response
        echo json_encode($response);
    }

    public function save() {
        $error = false;

        // plugin support
        JPluginHelper::importPlugin('joomlamailer');
        $dispatcher = JDispatcher::getInstance();

        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $archiveDir = $params->get('params.archiveDir', '/administrator/components/com_joomailermailchimpintegration/archive');

        // get POST data
        $creationDate       = JRequest::getVar('cid', 0, 'post', 'string');
        $action		        = JRequest::getVar('action', 'save', 'post', 'string');
        $campaignName       = JRequest::getVar('campaign_name', 0, 'post', 'string');
        $campaignNameEsc    = JApplication::stringURLSafe($campaignName);
        $subject            = stripslashes(JRequest::getVar('subject', 0, 'post', 'string'));
        $fromName           = stripslashes(str_ireplace(array('"', '@'),array(' ','(at)'), JRequest::getVar('from_name', 0, 'post', 'string')));
        $fromEmail          = JRequest::getVar('from_email', 0, 'post', 'string');
        $replyEmail         = JRequest::getVar('reply_email', 0, 'post', 'string');
        $confirmationEmail  = JRequest::getVar('confirmation_email', 0, 'post', 'string');
        $textOnly	        = JRequest::getVar('text_only', 0, 'post', 'int');
        $textOnlyContent    = JRequest::getVar('text_only_content', '', 'post', 'string');
        $templateFolder     = JRequest::getVar('template', 0, 'post', 'string');
        $facebookShareIt    = JRequest::getVar('facebookShare', 0, 'post', 'string');
        $facebookShareDesc  = JRequest::getVar('facebookShareDesc', '', 'post', 'string');
        $fbImage            = JRequest::getVar('fbImage', '', 'post', 'string');

        $gaEnabled          = JRequest::getVar('gaEnabled', 0, 'post', 'int');
        $gaExcluded         = JRequest::getVar('gaExcluded', '', 'post', 'string');
        $gaSource           = JApplication::stringURLSafe(JRequest::getVar('gaSource', 'newsletter', 'post', 'string'));
        $gaMedium           = JApplication::stringURLSafe(JRequest::getVar('gaMedium', 'email', 'post', 'string'));
        $gaName             = JApplication::stringURLSafe(JRequest::getVar('gaName', $campaignNameEsc, 'post', 'string'));

        // define abs path for regex
        $absPath = '$1="' . JURI::root() . '$2$3';
        $imagepath = '$1="' . JURI::root() . 'administrator/components/com_joomailermailchimpintegration/templates/' . $templateFolder . '/$2$3';

        // get folder id or name
        $folder_id = JRequest::getVar('folder_id', 0, 'post', 'int');
        $folder_name = JRequest::getVar('folder_name', 0, 'post', 'string');
        if (!$folder_id && $folder_name) {
            $folder_id = $this->getModel('create')->createFolder($folder_name);
        }

        if ($textOnly) {
            $template = $textOnlyContent;
            // create google analytics tracking links
            if ($gaEnabled) {
                $ga = 'utm_source=' . $gaSource . '&utm_medium=' . $gaMedium . '&utm_campaign=' . $gaName;
                $gaEx = explode("\n", $gaExcluded);
                for ($i = 0; $i < count($gaEx); $i++) {
                    $gaEx[$i] = trim($gaEx[$i]);
                }
                $gaEx[] = '*|UNSUB|*';

                $regex = '#https?://.*?(?:\s|\n|\r)#i';
                preg_match_all($regex, $template, $templateLinks, PREG_PATTERN_ORDER);

                if (isset($templateLinks[0])) {
                    foreach ($templateLinks[0] as $link) {
                        $glue = (strstr($link, '?'))? $glue = '&' : $glue = '?';
                        $oldHref = substr($link, 0, -1);
                        $addGA = true;
                        foreach($gaEx as $ex){
                            if (stristr($link, $ex)) {
                                $addGA = false;
                                break;
                            }
                        }
                        if ($addGA) {
                            $link = str_replace(array("\s","\n","\r"," ",'%'), array('','','','', '\%'), $link);
                            $template = preg_replace('%' . $link . '(\s|\n|\r)%i', $oldHref . $glue . $ga . '$1', $template);
                        }
                    }
                }
            }

            $filename = JPATH_SITE . $archiveDir . '/' . $campaignNameEsc . '.txt';
            if (!JFile::write($filename, $template)) {
                $error = true;
            }
        } else {
            $introText = JRequest::getVar('intro', '', 'post', 'string', JREQUEST_ALLOWRAW);

            // display popular articles?
            $popularlist = '';
            $popularCheckbox = JRequest::getVar('populararticles', 0, 'post', 'int');
            $populararticlesAmount = JRequest::getVar('populararticlesAmount', 5, 'post', 'int');
            $popularEx = JRequest::getVar('popExclude', false, 'post');
            $popularIn = JRequest::getVar('popInclude', false, 'post');
            // include K2 in populars?
            $popularK2Checkbox = JRequest::getVar('populark2', 0, 'post', 'int');
            $popularK2Ex = JRequest::getVar('popk2Exclude', false, 'post');
            $popularK2In = JRequest::getVar('popk2Include', false, 'post');
            // only K2 articles in populars?
            $popularK2Only = JRequest::getVar('populark2_only', 0, 'post', 'int');

            // convert relative to absolute href paths
            $introText = preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|[.]|\+)[^"]*"))#i', $absPath, $introText);
            // open the template file
            $filename = JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/templates/' . $templateFolder . '/template.html';
            $template = JFile::read($filename, false, filesize($filename));

            // popular articles
            $regex = '!<#populararticles#[^>]*>(.*)<#/populararticles#>!is';
            if (preg_match($regex, $template, $populararticles)) {
                $populararticles = $populararticles[0];
                $regex = '!<#popular_repeater#[^>]*>(.*)<#/popular_repeater#>!is';
                if (preg_match($regex, $template, $popular_repeater)) {
                    $popular_repeater = $popular_repeater[0];
                }

                // create list of popular articles
                if ($popularCheckbox && !$popularK2Checkbox){
                    $query = 'SELECT c.id, c.title, c.hits FROM #__content as c
                    WHERE (c.state = 1 OR c.state = -2)
                    AND c.hits != 0
                    '.$where.'
                    ORDER BY c.hits DESC
                    LIMIT 0 , '.$populararticlesAmount;
                } else if ($popularCheckbox && $popularK2Checkbox && !$popularK2Only) {
                    $query = 'SELECT c.id, c.title, c.hits
                    FROM #__content as c
                    WHERE (c.state = 1 OR c.state = -2)
                    AND c.hits != 0
                    '.$where.'
                    UNION ALL SELECT k.id, k.title, k.hits
                    FROM #__k2_items as k
                    WHERE k.published = 1
                    AND k.hits != 0
                    '.$whereK2.'
                    ORDER BY hits DESC
                    LIMIT 0 , '.$populararticlesAmount;
                } else if ($popularCheckbox && $popularK2Checkbox && $popularK2Only)  {
                    $query = 'SELECT k.id, k.title, k.hits
                    FROM #__k2_items as k
                    WHERE k.published = 1
                    AND k.hits != 0
                    '.$whereK2.'
                    ORDER BY k.hits DESC
                    LIMIT 0 , '.$populararticlesAmount;
                }

                if ($popularCheckbox){
                    $this->db->setQuery($query);
                    $popular = $this->db->loadObjectList();
                    $i = 0;
                    foreach ($popular as $pop){
                        if ($i++ > 5) { break; }

                        $query = $this->db->getQuery(true)
                            ->select($this->db->qn('id'))
                            ->from($this->db->qn('#__content'))
                            ->where($this->db->qn('title') . ' = ' . $this->db->q($pop->title));
                        $this->db->setQuery($query);
                        if ($this->db->loadResult()) {
                            $url = '<a href="' . JURI::root() . 'index.php?option=com_content&view=article&id=' . $pop->id . '">' . $pop->title . '</a>';
                        } else {
                            $url = '<a href="' . JURI::root() . 'index.php?option=com_k2&view=item&id=' . $pop->id . '">' . $pop->title . '</a>';
                        }
                        $popularlist .= str_ireplace('<#popular_title#>', $url, $popular_repeater);
                    }
                }
                $popularlist = preg_replace('!<#popular_repeater#[^>]*>(.*)<#/popular_repeater#>!is', $popularlist, $populararticles);

                $toReplace = array('<#populararticles#>', '<#/populararticles#>', '<#popular_repeater#>', '<#/popular_repeater#>');
                $popularlist = str_ireplace($toReplace, '', $popularlist);
            }

            $filename = JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/templates/' . $templateFolder . '/template.html';
            $template = JFile::read($filename, false, filesize($filename));

            // create absolute image paths
            $imagepath = ' src="' . JURI::base() . 'components/com_joomailermailchimpintegration/templates/' . $templateFolder . '/' . '$2$3';
            $template  = preg_replace('#(src)="([^:"]*)("|(?:(?:%20|\s|[.]|\+)[^"]*"))#i', $imagepath, $template);
            $imagepath = " url('" . JURI::base() . "components/com_joomailermailchimpintegration/templates/" . $templateFolder . "/";
            $template  = preg_replace('#(\s*)url?\([\'"]?[../]*[\'"]?#i', $imagepath, $template);
            $imagepath = ' background="' . JURI::root() . 'administrator/components/com_joomailermailchimpintegration/templates/' . $templateFolder . '/';
            $template  = preg_replace('#(\s*)background=[\'"]?[../]*[\'"]?#i', $imagepath, $template);

            JRequest::setVar('template_folder', $templateFolder);

            // call plugin event to insert content
            $dispatcher->trigger('insert', array(&$template));

            // modify paths of intro-text
            // remove tiny mce stuff like mce_src="..."
            $introText = preg_replace('(mce_style=".*?")', '', $introText);
            $introText = preg_replace('(mce_src=".*?")',   '', $introText);
            $introText = preg_replace('(mce_href=".*?")',  '', $introText);
            $introText = preg_replace('(mce_bogus=".*?")', '', $introText);
            // convert relative to absolute paths
            $absPath   = '$1="' . JURI::root() . '$2$3';
            $introText = preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|[.]|\+)[^"]*"))#i', $absPath, $introText);
            // end paths intro-text

            // insert intro-text
            $template = str_ireplace('<#intro_content#>', $introText, $template);
            // insert page title
            $template = str_ireplace('<#subject#>', $subject, $template);

            //insert popular articles
            if ($popularlist && $popularCheckbox) {
                $popularlist = str_ireplace('$', '\$', $popularlist);
                $template = preg_replace('!<#populararticles#[^>]*>(.*?)<#/populararticles#>!is', $popularlist, $template);
            } else {
                $template = preg_replace('!<#populararticles#[^>]*>(.*?)<#/populararticles#>!is', '', $template);
            }

            //insert facebook share link
            if ($facebookShareIt) {
                $template = preg_replace('!<#facebook_share#[^>]*>(.*?)<#/facebook_share#>!is', $fbs, $template);
                $metaData = "<meta name=\"title\" content=\"{$campaignName}\" />\n".
                    "<meta name=\"description\" content=\"{$facebookShareDesc}\" />\n".
                    "<link rel=\"image_src\" href=\"{$fbImage}\" />\n";
                $metaData = preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|[.]|\+)[^"]*"))#i', $absPath, $metaData);
                $template = str_ireplace('</head>', $metaData . '</head>', $template);
            } else {
                $template = preg_replace('!<#facebook_share#[^>]*>(.*?)<#/facebook_share#>!is', '', $template);
            }

            // remove unused placeholders
            $template = preg_replace('!<#([^#]+)#>.*?<#/\1#>!s', '', $template);
            $template = preg_replace('/<#[^#]+#>/', '', $template);

            // create google analytics tracking links
            if ($gaEnabled) {
                $ga = 'utm_source=' . $gaSource . '&utm_medium=' . $gaMedium . '&utm_campaign=' . $gaName . '"';
                $gaEx = explode("\n", $gaExcluded);
                for($i=0;$i<count($gaEx);$i++){
                    $gaEx[$i] = trim($gaEx[$i]);
                }
                $gaEx[] = '*|UNSUB|*';

                $regex = '#<a(.*?)>(.*?)</a>#i';
                preg_match_all($regex, $template, $templateLinks, PREG_PATTERN_ORDER);

                if (isset($templateLinks[0])) {
                    foreach($templateLinks[0] as $link){

                        preg_match_all('#((href)="(?!\.css)[^"]+)"#i' , $link, $oldLink, PREG_PATTERN_ORDER);
                        if (isset($oldLink[0][0])) {
                            $glue = (strstr($oldLink[0][0], '?'))? $glue = '&' : $glue = '?';
                            $oldHref = substr($oldLink[0][0], 0, -1);
                            $addGA = true;

                            foreach ($gaEx as $ex) {
                                if (stristr($oldHref,$ex)) { $addGA = false; }
                            }
                            if ($addGA) {
                                $newLink  = preg_replace('#((href)="(?!\.css)[^"]+)"#i', $oldHref . $glue . $ga . '"', $link);
                                $template = str_ireplace($oldLink[0][0], $oldHref . $glue . $ga . '"', $template);
                            }
                        }
                    }
                }
            }

            // prevent preview from being cached
            $metaData = "\n<meta http-Equiv=\"Cache-Control\" Content=\"no-cache\">\n".
                "<meta http-Equiv=\"Pragma\" Content=\"no-cache\">\n".
                "<meta http-Equiv=\"Expires\" Content=\"0\">\n".
                "<base href=\"\">\n";
            if (!stristr($template, "<head>")) {
                $template = str_ireplace('<html>', '<html><head>' . $metaData . '</head>', $template);
            } else {
                $template = str_ireplace('</head>', $metaData.'</head>', $template);
            }

            // create html version
            $filename = JPATH_SITE . $archiveDir . '/' . $campaignNameEsc .'.html';
            if (!JFile::write($filename, $template)) {
                $error = true;
            }

            // create txt version
            if (!$error) {
                $txtContent = $template;
                $txtContent = preg_replace("!<head[^>]*>(.*?)</head>!is", '', $txtContent);
                $txtContent = preg_replace("!<style[^>]*>(.*?)</style>!is", '', $txtContent);
                $txtContent = preg_replace("!<forwardtoafriend[^>]*>(.*?)</forwardtoafriend>!is", 'Forward to a friend: *|FORWARD|*', $txtContent);
                $txtContent = preg_replace("!<preferences[^>]*>(.*?)</preferences>!is", 'Preference center: *|UPDATE_PROFILE|*', $txtContent);
                $txtContent = preg_replace("!<unsubscribe[^>]*>(.*?)</unsubscribe>!is", '*|UNSUB|*', $txtContent);
                $txtContent = preg_replace("!<webversion[^>]*>(.*?)</webversion>!is", '*|ARCHIVE|*', $txtContent);
                $txtContent = strip_tags($txtContent);
                $txtContent = htmlspecialchars($txtContent);
                $txtContent = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n\n", $txtContent);
                $txtContent = preg_replace("/^ +/m", '', $txtContent);
                $txtContent = $campaignNameEsc . "\n" . $txtContent;

                $filename = JPATH_SITE . $archiveDir . '/' . $campaignNameEsc . '.txt';
                @JFile::write($filename, $txtContent);
            }
        }

        // set the redirection link and message
        if ($error) {
            foreach ($_POST as $k => $v) {
                JRequest::setVar($k, $v);
            }

            if ($creationDate)      { JRequest::setVar('cid',   $creationDate); }
            if ($campaignName)	    { JRequest::setVar('cn',    $campaignName); }
            if ($subject)           { JRequest::setVar('sj',    $subject); }
            if ($fromName)          { JRequest::setVar('fn',    $fromName); }
            if ($fromEmail)         { JRequest::setVar('fe',    $fromEmail); }
            if ($replyEmail)        { JRequest::setVar('re',    $replyEmail); }
            if ($confirmationEmail) { JRequest::setVar('ce',    $confirmationEmail); }
            if ($templateFolder)    { JRequest::setVar('tpl',   $templateFolder); }

            if ($popularCheckbox)   { JRequest::setVar('pop',   $popularCheckbox); }
            if ($popularIn)         { JRequest::setVar('pin',   implode(';',$popularIn)); }
            if ($popularEx)         {  JRequest::setVar('pex',   implode(';',$popularEx)); }
            if ($popularK2Checkbox) { JRequest::setVar('pk2',   $popularK2Checkbox); }
            if ($popularK2In)       { JRequest::setVar('pk2in', implode(';',$popularK2In)); }
            if ($popularK2Ex)       { JRequest::setVar('pk2ex', implode(';',$popularK2Ex)); }
            if ($popularK2Only)     { JRequest::setVar('pk2o',  $popularK2Only); }

            if ($introText)         { JRequest::setVar('intro',  urlencode(htmlentities(urlencode($introText)))); }

            if ($gaSource)          { JRequest::setVar('gaS',    urlencode(htmlentities(urlencode($gaSource)))); }
            if ($gaMedium)          { JRequest::setVar('gaM',    urlencode(htmlentities(urlencode($gaMedium)))); }
            if ($gaName)            { JRequest::setVar('gaN',    urlencode(htmlentities(urlencode($gaName  )))); }
            if ($gaExcluded)        { JRequest::setVar('gaE',    $gaExcluded); }

            JRequest::setVar('view', 'create');
            JRequest::setVar('layout', 'default');
            JRequest::setVar('action', JRequest::getVar('action', ''));
            JRequest::setVar('hidemainmenu', 0);
            JRequest::setVar('offset', 0);

            jimport('joomla.error.error');
            JError::raiseWarning(100, JText::sprintf('JM_CAMPAIGN_CREATION_FAILED', $archiveDir));

            parent::display();
        } else {
            $timeStamp = time();
            $postData = array();
            foreach ($_POST as $key => $value) {
                if (in_array($key, array('cid', 'offset', 'activeTab', 'option', 'task', 'action', 'boxchecked', 'controller', 'type'))) {
                    continue;
                }
                if (is_string($value) && stristr($value, 'http')) {
                    $value = urlencode(htmlentities(urlencode($value)));
                }

                $postData[$key] = $value;
            }

            $query = $this->db->getQuery(true);

            // store campaign details locally
            if ($creationDate && $action != 'copy') {
                $query->update($this->db->qn('#__joomailermailchimpintegration_campaigns'))
                    ->set($this->db->qn('subject') . ' = ' . $this->db->q($subject))
                    ->set($this->db->qn('from_name') . ' = ' . $this->db->q($fromName))
                    ->set($this->db->qn('from_email') . ' = ' . $this->db->q($fromEmail))
                    ->set($this->db->qn('reply') . ' = ' . $this->db->q($replyEmail))
                    ->set($this->db->qn('confirmation') . ' = ' . $this->db->q($confirmationEmail))
                    ->set($this->db->qn('creation_date') . ' = ' . $this->db->q($timeStamp))
                    ->set($this->db->qn('cdata') . ' = ' . $this->db->q(json_encode($postData)))
                    ->set($this->db->qn('folder_id') . ' = ' . $this->db->q($folder_id))
                    ->where($this->db->qn('creation_date') . ' = ' . $this->db->q($creationDate));
            } else {
                $query->insert($this->db->qn('#__joomailermailchimpintegration_campaigns'))
                    ->set($this->db->qn('name') . ' = ' . $this->db->q($campaignName))
                    ->set($this->db->qn('subject') . ' = ' . $this->db->q($subject))
                    ->set($this->db->qn('from_name') . ' = ' . $this->db->q($fromName))
                    ->set($this->db->qn('from_email') . ' = ' . $this->db->q($fromEmail))
                    ->set($this->db->qn('reply') . ' = ' . $this->db->q($replyEmail))
                    ->set($this->db->qn('confirmation') . ' = ' . $this->db->q($confirmationEmail))
                    ->set($this->db->qn('creation_date') . ' = ' . $this->db->q($timeStamp))
                    ->set($this->db->qn('cdata') . ' = ' . $this->db->q(json_encode($postData)))
                    ->set($this->db->qn('folder_id') . ' = ' . $this->db->q($folder_id));
            }
            $this->db->setQuery($query);

            try {
                $this->db->execute();
                $msg = sprintf(JText::_('JM_DRAFT_SAVED'), $campaignName);
                $this->app->enqueueMessage($msg);
            } catch (Exception $e) {
                jimport('joomla.error.error');
                JError::raiseWarning(100, $e->getMessage());
            }

            JRequest::setVar('view', 'send');
            JRequest::setVar('layout', 'default');
            JRequest::setVar('campaign', $timeStamp);
            JRequest::setVar('hidemainmenu', 0);

            parent::display();
        }
    }
}
