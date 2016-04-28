<?php
/**
 * @version             $Id: joomsharebar.php 144 2014-09-09 21:03:30Z roy $
 * @package             joomsharebar
 * @copyright           Copyright (C) 2013 JooMarketer.com. All rights reserved.
 * @license             GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgSystemJoomShareBar extends JPlugin
{
    public function __construct(&$subject, $config)
    {
        $this->_continue = 0;
        $this->skyscraperOrBar = "";
        $this->frontpage = 0;
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    /************************
     * onAfterRoute
     *************************/
    public function onAfterRoute ()
    {
        $app = JFactory::getApplication();

        if($app->isAdmin()) {

            $admdocument = JFactory::getDocument();
            $admdocument->addScript(JURI::root() .'plugins/system/joomsharebar/joomsharebar/admin/formfields/jscolor/jscolor.js', 'text/javascript');
        }

        if ($this->continueOrNot($this->getCurrentView(), $this->params->get('showInArticleView'), $this->params->get('showBarInCategoryView'), $this->params->get('showOnFrontpage')))
            $this->_continue = 1;
    }

    /************************
     * onAfterDispatch
     *************************/
    function onAfterDispatch()
    {
        if (!$this->_continue)
            return;

        // Add CSS
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::base() . 'plugins/system/joomsharebar/joomsharebar/css/jsb.css');

        // Get user defined styles
        $currentView = $this->getCurrentView();
        $addView = "_" . ucfirst($currentView) . "View"; // Set the Current View for getting variables
        $annotation = $this->params->get("buttonLayout$addView"); // Get the selected Annotation for the current view
        $user_styles = $this->getUserStyles($currentView, $this->params);

        // Slide / Follow options
        $verticalSlide = $this->params->get("verticalSlide");
        $horizontalSlide = $this->params->get("horizontalSlide");

        // Tranformation: Vertical Skyscraper -> Horizontal Bar when screen width < xxx pixels
        $transformVerticalHorizontal = $this->params->get("transformVerticalHorizontal");
        $transformVerticalHorizontalPixels = $this->params->get("transformVerticalHorizontalPixels");
        $skyscraperLeftMarginFromCenter = $this->params->get("skyscraperLeftMarginFromCenter");

        // Lazy Loading Functionality
        $lazyLoadingType = "default";

        $content = substr(JRequest::getCmd("option"),4);

        // Build JS needed vars and add them into document
        $js = "var buttonSize = '8'; var view = '$currentView'; var verticalSlide = '$verticalSlide'; var horizontalSlide = '$horizontalSlide'; var lazyLoadingType = '$lazyLoadingType'; var left_margin = '$skyscraperLeftMarginFromCenter'; var user_styles = '$user_styles'; var annotation = '$annotation'; var transform = '$transformVerticalHorizontal'; var content = '$content';";
        $document->addScriptDeclaration($js);

        // Add JQuery if Needed
        $preventJQueryLoad = $this->params->get("prevent_jquery_load");
        if ($preventJQueryLoad != 1)
            $document->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");

        // Add other required javascripts
        $document->addScript(JURI::base() . 'plugins/system/joomsharebar/joomsharebar/js/jsb.min.js');
        $document->addScript(JURI::base() . 'plugins/system/joomsharebar/joomsharebar/socialite/socialite_new.min.js');
    }

    /************************
     * onContentBeforeDisplay
     *************************/
    public function onContentBeforeDisplay($context, &$article, &$params, $limitstart=0)
    {
        if (!$this->_continue)
            return;

        // Only on Horizontal Bar and/or Category View
        $currentView = $this->getCurrentView();

        if (($this->skyscraperOrBar == "horizontal_bar") || ($currentView=="category"))
        {
            $url = $this->getUrl($article);
            $title  = htmlentities($article->title, ENT_QUOTES, "UTF-8");
            $add_class = "_hori";

            if ($currentView=="article") // Single Article
            {
                $top_bottom = $this->params->get('horizontalBarTopBottom');
                if ($top_bottom == "top")
                    $article->text = $this->getContent($article, $currentView, $url, $title, $add_class) . $article->text;
                else if ($top_bottom == "bottom")
                    $article->text = $article->text . $this->getContent($article, $currentView, $url, $title, $add_class);
            }
            else if ($currentView=="category")
            {
                $top_bottom_cat_view = $this->params->get('catViewTopBottom');
                if ($top_bottom_cat_view == "top")
                    $article->introtext = $this->getContent($article, $currentView, $url, $title, $add_class) . $article->introtext;
                else if ($top_bottom_cat_view == "bottom")
                    $article->introtext = $article->introtext . $this->getContent($article, $currentView, $url, $title, $add_class);
            }
        }
    }

    /************************
     * onAfterRender
     *************************/
    function onAfterRender()
    {
        if (!$this->_continue)
            return;

        $currentView = $this->getCurrentView();

        if (($this->skyscraperOrBar == "skyscraper") && ($currentView =="article"))
        {
            $html = JResponse::getBody();

            if ($html == '')
            {
                return;
            }

            $url = $this->getUrl("");

            $ids = explode(':',JRequest::getString('id'));
            $article_id = $ids[0];
            $article = JTable::getInstance("content");
            $article->load($article_id);
            $title = $article->get("title");

            $new_html = "<!-- START: JOOMSHAREBAR -->" . $this->getContent($article, $currentView, $url, $title, "_vert") . "<!-- END: JOOMSHAREBAR --></body>";
            $html = str_replace('</body>', $new_html, $html);

            JResponse::setBody($html);
        }
    }

    /***************************************************************************************
     * Start Helper Functions
     ****************************************************************************************/

    /************************
     * continueOrNot
     *************************/
    private function continueOrNot($currentView, $showInArticleView, $showBarInCategoryView, $showOnFrontpage)
    {
        $this->skyscraperOrBar = $this->params->get('skyscraperOrBar');

        $app = JFactory::getApplication();
        $menu = $app->getMenu();
        $lang = JFactory::getLanguage();
        // next can be used when articles are used in combination with the home button active:
        //if (($menu->getActive() == $menu->getDefault($lang->getTag())) && ($_SERVER['PHP_SELF'] == "/index.php"))
        if ($menu->getActive() == $menu->getDefault($lang->getTag())) {
            $this->frontpage = 1;
            $this->skyscraperOrBar = "skyscraper";
        }

        if($app->isAdmin()) {
            return false;
        }

        $currentOption = JRequest::getCmd("option");

        switch($currentOption)
        {
            case "com_content":

                if($this->frontpage == 1)
                {
                    if (!$showOnFrontpage)
                        return false;
                    else if ($showOnFrontpage)
                        return true;
                }
                if ($showOnFrontpage AND ($this->frontpage == 1))
                    return true;
                if($showInArticleView AND (strcmp("article", $currentView) == 0))
                    return true;
                if($showBarInCategoryView AND (strcmp("category", $currentView) == 0))
                    return true;
                else
                    return false;

                break;
        }

    }

    /************************
     * getCurrentView
     *************************/
    private function getCurrentView()
    {
        $currentView = JRequest::getWord("view");

        if ($currentView == "item" || $currentView == "featured" || $this->frontpage == 1)
            $currentView = "article";
        else if ($currentView == "itemlist" /*|| $currentView == "featured"*/ || $currentView == "latest")
            $currentView = "category";

        return $currentView;
    }

    /************************
     * getUrl
     *************************/
    private function getUrl($article)
    {
        $url = 'http';
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] == 443) {

            $url .= "s";
        }
        $url .= "://" . $_SERVER["SERVER_NAME"];

        if (is_string($article))
        {
           $url .= $_SERVER["REQUEST_URI"];
        }
        else
        {
            $url .= JRoute::_(ContentHelperRoute::getArticleRoute($article->id, $article->catid));
        }

        return $url;
    }

    /************************
     * getContent
     *************************/
    private function getContent(&$article, $currentView, $url, $title, $add_class)
    {
        $addView = "_" . ucfirst($currentView) . "View"; // Set the Current View for getting variables

        $excludedCats = $this->params->get('excludeCats');
        if(!empty($excludedCats)){
            $excludedCats = explode(',', $excludedCats);
        }
        settype($excludedCats, 'array');
        JArrayHelper::toInteger($excludedCats);

        $excludeArticles = $this->params->get('excludeArticles');
        if(!empty($excludeArticles)){
            $excludeArticles = explode(',', $excludeArticles);
        }
        settype($excludeArticles, 'array');
        JArrayHelper::toInteger($excludeArticles);

        if(in_array($article->catid, $excludedCats) OR in_array($article->id, $excludeArticles)){
            return "";
        }

        $user_styles = $this->getUserStyles($currentView, $this->params);

        // Generate HTML based on enabled buttons with ordering
        $html = '<div id="joomsharebar"><div class="joomsharebar' . $add_class . '" style="' . $user_styles . '">';

        $params_as_array = $this->params->toArray();
        $allButtons = preg_grep ("/^order/i", array_keys($params_as_array)); // variable names start with order (e.g. orderTwitterBtn)
        $exclCatViewButtons = preg_grep ("/^exclCatView/i", array_keys($params_as_array)); // get excluded from Category View variable names (e.g. exclCatViewTwitterBtn)

        $enabledButtons = array();
        foreach ( $allButtons as $key )
        {
            if ($params_as_array[$key] != "disable") // only add enabled buttons to array
                $enabledButtons[$key] = $params_as_array[$key];
        }

        // Do not display the buttons that have been disabled for Category View
        if ($currentView == "category")
        {
            foreach ( $exclCatViewButtons as $key )
            {
                if ($params_as_array[$key] == "disableCatView") // Check if in Category View and user has disabled this button in Cat View
                {
                    $key = str_replace("exclCatView", "order", $key);
                    unset($enabledButtons[$key]);
                }
            }
        }

        asort($enabledButtons);
        foreach ($enabledButtons as $key => $val)
        {
            $annotation = $this->params->get("buttonLayout$addView"); // Get the selected Annotation for the current view
            $preLoadedDesign = $this->params->get("preLoadedDesign_$annotation"); // Get the selected design preset for the pre-loaded buttons

            $getButtonCall = str_replace("order", "get", $key);
            if ($this->frontpage == 1)
            {
                if (strpos($getButtonCall, "Fblike") AND $this->params->get('enableFBPageUrl'))
                {
                    $url_fb = $this->params->get('FBPageUrl');
                    $html .= '<div class="joomsharebar-button' . $add_class . '">'.$this->$getButtonCall($this->params, $url_fb, $title, $annotation, $preLoadedDesign).'</div>';
                }
                else
                {
                    $html .= '<div class="joomsharebar-button' . $add_class . '">'.$this->$getButtonCall($this->params, $url, $title, $annotation, $preLoadedDesign).'</div>';
                }
            }
            else
            {
                $html .= '<div class="joomsharebar-button' . $add_class . '">'.$this->$getButtonCall($this->params, $url, $title, $annotation, $preLoadedDesign).'</div>';
            }
        }

        // End buttons box
        $html .= '<div class="joomsharebar-button' . $add_class . '"><div class="want_this' . $add_class . '"><a href="http://www.joomarketer.com/joomla-extensions/joomsharebar" title="JoomShareBar - Joomla & K2 Ultimate Social Sharing" target="_blank">JoomShareBar</a></div></div>
        </div></div>
        <div style="clear:both;"></div>
        ';

        return $html;
    }

    /************************
     * getUserStyles
     *************************/
    private function getUserStyles($view, $params)
    {
        $document = JFactory::getDocument();
        $addView = "_" . ucfirst($view) . "View"; // Set the Current View for getting variables

        $container_preset = $params->get("container_preset");
        $marginTop = $params->get("marginTop$addView");
        $marginBottom = $params->get("marginBottom$addView");



        if (($this->skyscraperOrBar == "skyscraper") && $view == "article")
        {
            $alignment_buttons_article = $this->params->get('alignmentButtonsArticle');
            if ($alignment_buttons_article == "left")
                $margin_alignment = "margin-left: 0; margin-right: auto;";
            else if ($alignment_buttons_article == "right")
                $margin_alignment = "margin-left: auto; margin-right: 0;";
            else
                $margin_alignment = "margin-left: auto; margin-right: auto;";

            $style_align_buttons_article_view = '.socialite { text-align: ' . $alignment_buttons_article . '!important; '. $margin_alignment . '}';
            $document->addStyleDeclaration( $style_align_buttons_article_view );

            $skyscraperLeftMarginFromCenter = $this->params->get('skyscraperLeftMarginFromCenter');
            $skyscraperMarginFromTop = $this->params->get('skyscraperMarginFromTop');

            /*if (preg_match('/iPhone|iPod|iPad|BlackBerry|Android/', $_SERVER['HTTP_USER_AGENT']))
            {
                $skyscraperLeftMarginFromCenter = "-480px";
                $skyscraperMarginFromTop = "320px";
            }*/
        }
        else if (($this->skyscraperOrBar == "horizontal_bar") || $view == "category")
        {
            $skyscraperLeftMarginFromCenter = "0px";
            $skyscraperMarginFromTop = "0px";
        }

        $style_html = "margin-left: $skyscraperLeftMarginFromCenter!important; top: $skyscraperMarginFromTop; margin-top: $marginTop; margin-bottom: $marginBottom; ";

        if ($container_preset == "none" || $view == "category")
        {
            $bGColor = '#'.$params->get("bGColor$addView");
            if (strtolower($bGColor) == "#none")
                $bGColor = "transparent";

            $height = $params->get("height$addView");
            $width = $params->get("width$addView");
            $roundedCorners = $params->get("roundedCorners$addView");
            $borderStyle = $params->get("borderStyle$addView");
            $borderWidth = $params->get("borderWidth$addView");
            $borderColor = $params->get("borderColor$addView");
            $shadow = $params->get("shadow$addView");

            $style_html .= "background: $bGColor; height: $height; width: $width; border-radius: $roundedCorners; border: $borderWidth $borderStyle #$borderColor; box-shadow: $shadow;";
        }

        return $style_html;
    }

    /************************
     * getFbLikeBtn
     *************************/
    private function getFblikeBtn($params, $url, $title, $annotation, $preLoadedDesign)
    {
        if ($annotation == "vertical")
            $fblikeLayout = "box_count";
        else
            $fblikeLayout = "button_count";

        $fblikeVerb=$params->get("fblikeVerb");
        $fblikeFont=$params->get("fblikeFont");
        $fblikeColor=$params->get("fblikeColor");
        $fblikeSend=$params->get("fblikeSend");

        $html = '<a	href="http://www.facebook.com/sharer.php?u='.$url.'&amp;t='.$title.'"
                class="socialite facebook-like '.$annotation.' '.$preLoadedDesign.'"
                data-href="'.$url.'"
                data-layout="'.$fblikeLayout.'"
                data-send="'.$fblikeSend.'"
                data-action="'.$fblikeVerb.'"
                data-font="'.$fblikeFont.'"
                data-colorscheme="'.$fblikeColor.'"
                target="_blank">
                <span class="vhidden">Share on Facebook</span>
            </a>
        ';

        return $html;
    }

    /************************
     * getTwitterBtn
     *************************/
    private function getTwitterBtn($params, $url, $title, $annotation, $preLoadedDesign)
    {
        $twitterCount=$annotation;
        $twitterBtnSize=$params->get("twitterBtnSize");
        $twitterName=$params->get("twitterName");
        $twitterRecommended=$params->get("twitterRecommended");
        $twitterHashtag=$params->get("twitterHashtag");
        $twitterlanguage=$params->get("twitterLanguage");

        $html = '<a	href="http://twitter.com/share"
                class="socialite twitter-share '.$annotation.' '.$preLoadedDesign.'"
                data-text="'.$title.'"
                data-url="'.$url.'"
                data-count="'.$twitterCount.'"
                data-size="'.$twitterBtnSize.'"
                data-via="'.$twitterName.'"
                data-hashtags="'.$twitterHashtag.'"
                data-related="'.$twitterRecommended.'"
                data-lang="'.$twitterlanguage.'"
                target="_blank">
                <span class="vhidden">Share on Twitter</span>
            </a>
        ';

        return $html;
    }

    /************************
     * getGoogleplusBtn
     *************************/
    private function getGoogleplusBtn($params, $url, $title, $annotation, $preLoadedDesign)
    {
        $googleplusAnnotation = "";
        if ($annotation == "vertical")
        {
            $googleplusBtnSize = "tall"; // G+ button requires tall button size for vertical annotation
        }
        else
        {
            $googleplusBtnSize=$params->get("googleplusBtnSize");
            if ($googleplusBtnSize == "tall")
                $googleplusBtnSize = "medium";
        }

        $html = '
            <a	href="https://plus.google.com/share?url='.$url.'"
                class="socialite googleplus-one '.$annotation.' '.$preLoadedDesign.'"
                data-size="'.$googleplusBtnSize.'"
                data-annotation="'.$googleplusAnnotation.'"
                data-href="'.$url.'"
                target="_blank">
                <span class="vhidden">Share on Google+</span>
            </a>
        ';

        return $html;
    }

    /************************
     * getGoogleplusShareBtn
     *************************/
    private function getGoogleplusShareBtn($params, $url, $title, $annotation, $preLoadedDesign)
    {
        $googleShareBtnSize=$params->get("googleShareBtnSize");

        if ($annotation == "vertical")
        {
            $googleShareAnnotation = "vertical-bubble";
            $googleShareBtnSize = 60; // Vertical Bubble has fixed height
        }
        else
            $googleShareAnnotation = "bubble";

        $html = '
            <a	href="https://plus.google.com/share?url='.$url.'"
                class="socialite googleplus-share '.$annotation.' '.$preLoadedDesign.'"
                data-action="share"
                data-height="'.$googleShareBtnSize.'"
                data-annotation="'.$googleShareAnnotation.'"
                data-href="'.$url.'"
                target="_blank">
                <span class="vhidden">Share on Google+</span>
            </a>
        ';

        return $html;
    }

    /************************
     * getLinkedinBtn
     *************************/
    private function getLinkedinBtn($params, $url, $title, $annotation, $preLoadedDesign)
    {
        if ($annotation == "vertical")
            $linkedinCount = "top";
        else
            $linkedinCount = "right";

        $html = '
            <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url='.$url.'&amp;title='.$title.'"
                class="socialite linkedin-share '.$annotation.' '.$preLoadedDesign.'"
                data-url="'.$url.'"
                data-counter="'.$linkedinCount.'"
                data-showZero="true"
                target="_blank">
                <span class="vhidden">Share on LinkedIn</span>
            </a>
        ';

        return $html;
    }

    /************************
     * getBufferBtn
     *************************/
    private function getBufferBtn($params, $url, $title, $annotation, $preLoadedDesign)
    {
        $bufferCount=$annotation;

        $html = '
            <a href="http://bufferapp.com/add/?url='.$url.'&amp;media='.$url.'&amp;description='.$title.'"
                class="socialite bufferapp-button '.$annotation.' '.$preLoadedDesign.'"
                data-url="'.$url.'"
                data-count="'.$bufferCount.'"
                target="_blank">
                <span class="vhidden">Buffer It!</span>
            </a>
        ';

        return $html;
    }

    /************************
     * getPinterestBtn
     *************************/
    private function getPinterestBtn($params, $url, $title, $annotation, $preLoadedDesign)
    {
        $pinterestCount=$annotation;

        if ($pinterestCount == "vertical")
            $add_class = "pinterest-box-count"; // add an extra margin because of Pin it button size and alignment
        else
            $add_class = "";
        //$pinterestImage=$params->get("pinterestImage");

        $html = '<div class="' . $add_class . '">
            <a href="http://pinterest.com/pin/create/button/?url='.$url.'&amp;media=&amp;description='.$title.'"
                class="socialite pinterest-pinit '.$annotation.' '.$preLoadedDesign.'"
                data-count-layout="'.$pinterestCount.'"
                target="_blank">
                <span class="vhidden">Pin It!</span>
            </a></div>
        ';

        return $html;
    }
}

?>