<?php
/**
 * @version		$Id: mooaccordion.php 20196 2011-03-04 02:40:25Z mrichey $
 * @package		plg_content_mooaccordion
 * @copyright	Copyright (C) 2005 - 2011 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgContentMooAccordion extends JPlugin
{
        private $template1=array();
        private $template2=array();
        private $template3=array();
	/**
	 * Constructor
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	/**
	 * onContentPrepare
	 *
	 * Method is called by the view
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 */
	public function onContentPrepare( $context, &$article, &$params, $page=0 )
	{
                $app = JFactory::getApplication();
                $doc = JFactory::getDocument();
		if ( $app->isAdmin() || ! isset($article->text) ||! preg_match_all( "/mooaccordion/", $article->text, $matches )) return;
                if($doc->getType() != 'html') {
                    $article->text = preg_replace("/{content-mooaccordion\s*?.*?}/",'',$article->text);
                    return;
                }
                preg_match_all("/{content-mooaccordion\s*?.*?}/",$article->text,$containers);
                $containerparams = array();
                $style = array();
                if(count($containers) && count($containers[0]) && strlen($containers[0][0])>0) {
                    foreach($containers as $ckey=>$container) {
                        // strip the plugin tag from the output
                        $article->text = str_replace($container[0],'',$article->text);
                        // clean up the plugin tag
                        $search = array('/^{content-mooaccordion/','/}/');
                        foreach($search as $s) $container[0] = trim(preg_replace($s,'',$container[0]));
                        foreach(explode(' ',$container[0]) as $tmpparam) {
                            $tmpparam = explode("=",$tmpparam);
                            $tmpparam[0]=preg_replace(array('/^\'/','/\'$/'),array('',''),$tmpparam[0]);
                            $value = isset($tmpparam[1])?preg_replace(array('/^\'/','/\'$/'),array('',''),$tmpparam[1]):null;
                            if($tmpparam[0] == 'id') {
                                $containerid=$value;
                            }
                            if($tmpparam[0] == 'template') {
                                $style['template'.$value] = $this->loadCSSTemplate($value);
                            }
                            $containerparams[$containerid][$tmpparam[0]]=$value;
                        }
                    }
                }
                $search=array('{root}');
                $replace=array(JURI::root(true));
                $doc = JFactory::getDocument();
                $style[] = str_replace($search,$replace,$this->params->get('style',''));
                JHtml::_('behavior.framework',true);
                if(count($containerparams)) {
                    $doc->addScriptDeclaration("if(typeof(contentmooaccordion)=='undefined') var contentmooaccordion = {};");
                    foreach($containerparams as $key=>$value) {
                        $doc->addScriptDeclaration("contentmooaccordion['".$key."']=".json_encode((object)$value).";");
                    }
//                    $doc->addScriptDeclaration('var contentmooaccordion = '.json_encode((object)$containerparams).';');
                }
                $doc->addScript(JURI::root(true).'/media/plg_content_mooaccordion/js/mooaccordion.js');
                if(count($style)) $doc->addStyleDeclaration(implode("\n",$style));
	}
        private function loadCSSTemplate($id) {
            if($id >= 1 && $id <= 3) {
                $template = 'template'.$id;
                return $this->makeTemplate($template);
            } else {
                return '';
            }
        }
        private function makeTemplate($id) {
            $return = array();
            switch ($id) {
                case 'template1':
                $return[]=".template1opened .mooaccordionicon, .template1closed .mooaccordionicon {";
                $return[]="\tbackground: url(".JURI::root(true)."/media/plg_content_mooaccordion/images/template1arrow.png) no-repeat top left;";
                $return[]="\twidth:22px;\n\theight:22px;\n\tfloat:left;\n\tclear:none;\n\tposition:relative;\n\tleft:-24px;";
                $return[]="}";
                $return[]=".template1opened, .template1closed {\n\tpadding-left:24px;\n\tcursor:pointer;\n}";
                $return[]=".template1closed .mooaccordionicon {";
                $return[]="\tbackground-position: 0 -72px;";
                $return[]="}";
                $return[]=".template1opened .mooaccordionicon {";
                $return[]="\tbackground-position: 0 0;";
                $return[]="}";
                break;
                case 'template2':
                $return[]=".template2opened .mooaccordionicon, .template2closed .mooaccordionicon {";
                $return[]="\tbackground: url(".JURI::root(true)."/media/plg_content_mooaccordion/images/template2arrow.png) no-repeat top left;";
                $return[]="\twidth:8px;\n\theight:8px;\n\tfloat:left;\n\tclear:none;\n\tposition:relative;\n\tleft:-12px;top:2px;\n\tpadding:2px;\n\tmargin:2px;\n\tborder:1px solid #999;";
                $return[]="}";
                $return[]=".template2opened, .template2closed {\n\tpadding-left:20px;\n\tcursor:pointer;\n}";
                $return[]=".template2closed .mooaccordionicon {";
                $return[]="\tbackground-position: 2px -56px;";
                $return[]="}";
                $return[]=".template2opened .mooaccordionicon {";
                $return[]="\tbackground-position: 2px 2px;";
                $return[]="}";
                break;
                case 'template3':
                $return[]=".template3opened .mooaccordionicon, .template3closed .mooaccordionicon {";
                $return[]="\tbackground: url(".JURI::root(true)."/media/plg_content_mooaccordion/images/template3arrow.png) no-repeat top left;";
                $return[]="\twidth:24px;\n\theight:24px;\n\tfloat:left;\n\tclear:none;\n\tposition:relative;\n\tleft:-26px;";
                $return[]="}";
                $return[]=".template3opened, .template3closed {\n\tpadding-left:26px;\n\tcursor:pointer;\n}";
                $return[]=".template3closed .mooaccordionicon {";
                $return[]="\tbackground-position: 0px 0px;";
                $return[]="}";
                $return[]=".template3opened .mooaccordionicon {";
                $return[]="\tbackground-position: 0px -74px;";
                $return[]="}";
                break;
            }
            return implode("\n",$return);
        }
}
