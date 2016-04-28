<?php

/**
 * @copyright   (C) 2010 iJoomla, Inc. - All rights reserved.
 * @license  GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author  iJoomla.com webmaster@ijoomla.com
 * @url   http://www.ijoomla.com/licensing/
 * the PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript
 * are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at http://www.ijoomla.com/licensing/
 */

defined('_JEXEC') or die('Restricted Access');

JHtml::_('jquery.framework', false);
JHTML::_('behavior.modal');
JHtml::_('bootstrap.modal');

require_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."helpers".DS."reader.php");
$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");

$display_menus = "none";
$display_mtree = "none";
$display_zoo = "none";
$display_ktwo = "none";
$display_kunena = "none";
$display_easyblog = "none";

$document = JFactory::getDocument();

$document->addScript(JURI::root()."administrator/components/com_ijoomla_seo/javascript/scripts.js");
//$document->addScript(JURI::root()."administrator/components/com_ijoomla_seo/javascript/jquery.js");
$document->addScript(JURI::root()."administrator/components/com_ijoomla_seo/javascript/jquery.flot.js");
$document->addScript(JURI::root()."administrator/components/com_ijoomla_seo/javascript/jquery.flot.time.js");
include_once(JPATH_SITE.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."helpers".DS."amcharts".DS."daily_chart.php");
$document->addStyleSheet(JURI::root()."administrator/components/com_ijoomla_seo/css/g_graph.css");

?>

<style type="text/css">
    .chzn-drop{
        width:auto !important;
    }

    .chzn-container{
        text-align:left;
        width:110px !important;
    }

    .chzn-container a:link, table#stats a:visited{
        color:#000000 !important;
    }
</style>

<div class="row-fluid">
	<div class="span12">
    	<?php
			$form_button = '<form id="prform" name="prform" target="_blank" style="margin:0px; float:left;" method="post" action="http://www.ijoomla.com/index.php?option=com_digistore&controller=digistoreCart&task=add&pid[0]=94&cid[0]=94" onsubmit="return prodformsubmit4a60cb04c1341();">
								<input name="qty" value="1" type="hidden" />
								<input name="pid" id="product_id" value="94" type="hidden" />
								<input name="Button" type="submit" class="btn btn-warning" value="Buy Pro" />
							</form>';
		?>
        <div class="msg-content">
            <div class="alert alert-error light-msg">
                <span class="pull-left" style="line-height:35px;"><?php echo JText::_("COM_IJOOMLA_SEO_DASHBOARD_MSG"); ?></span>
                <?php
                    echo $form_button;
                ?>
                &nbsp;
                <span class="pull-left" style="line-height:35px;">
                    <a class="pull-left" href="http://seo.ijoomla.com/pricing/" target="_blank"><?php echo JText::_("COM_IJOOMLA_SEO_COMPARE"); ?></a>
                </span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="row-flow">

<div class="span8">

    <div>
        <!-- basic info button -->
        <div id="g_basicInfo" class="row-fluid g_outer_shell">
            <div class="g_middle_shell">
                <div class="g_inner_shell row-fluid">
                    
                    <div class="span3">
                        <div class="infobox infobox-blue infobox-dark">
                            <div class="infobox-icon">
                                <i class="icon-arrow-up"></i>
                            </div>
                            <div class="infobox-data">
                                <span class="total-orders">
                                    <a href="index.php?option=com_ijoomla_seo&controller=keys&cpanl=up"><?php echo $this->getKeysUp(); ?></a>
                                </span>
                            </div>
                            <div class="infobox-footer">
                                <div class="infobox-content"><a href="index.php?option=com_ijoomla_seo&controller=keys&cpanl=up">Up</a></div>
                            </div>
                        </div>
                    </div><!--//end box-1 -->
                    <div class="span3">
                        <div class="infobox infobox-green infobox-dark">
                            <div class="infobox-icon">
                                <i class="icon-arrow-down"></i>
                            </div>
                            <div class="infobox-data">
                                <span class="total-orders">
                                    <a href="index.php?option=com_ijoomla_seo&controller=keys&cpanl=down"><?php echo $this->getKeysDown(); ?></a>
                                </span>
                            </div>
                            <div class="infobox-footer">
                                <div class="infobox-content"><a href="index.php?option=com_ijoomla_seo&controller=keys&cpanl=down">Down</a></div>
                            </div>
                        </div>
                    </div><!--//end box-2 -->
                    <div class="span3">
                        <div class="infobox infobox-orange infobox-dark">
                            <div class="infobox-icon">
                                <i class="icon-pin"></i>
                            </div>
                            <div class="infobox-data">
                                <span class="total-orders">
                                    <a href="index.php?option=com_ijoomla_seo&controller=keys&cpanl=same"><?php echo $this->getKeysSame(); ?></a>
                                </span>
                            </div>
                            <div class="infobox-footer">
                                <div class="infobox-content"> <a href="index.php?option=com_ijoomla_seo&controller=keys&cpanl=same">No Change</a></div>
                            </div>
                        </div>
                    </div><!--//end box-3 -->
                    <div class="span3">
                        <div class="infobox infobox-pink infobox-dark">
                            <div class="infobox-icon">
                                <i class="icon-warning"></i>
                            </div>
                            <div class="infobox-data">
                                <span class="total-orders" id="missing-title">
                                    <a href="#">0</a>
                                </span>
                            </div>
                            <div class="infobox-footer">
                                <div class="infobox-content"><?php echo JText::_("COM_IJOOMLA_SEO_MISSING_TITLE"); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="infobox infobox-red infobox-dark">
                            <div class="infobox-icon">
                                <i class="icon-warning"></i>
                            </div>
                            <div class="infobox-data">
                                <span class="total-orders" id="missing-keys">
                                    <a href="#">0</a>
                                </span>
                            </div>
                            <div class="infobox-footer">
                                <div class="infobox-content"><?php echo JText::_("COM_IJOOMLA_SEO_MISSING_KEYWORDS"); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="infobox infobox-blue infobox-dark">
                            <div class="infobox-icon">
                                <i class="icon-warning"></i>
                            </div>
                            <div class="infobox-data">
                                <span class="total-orders" id="missing-desc">
                                    <a href="#">0</a>
                                </span>
                            </div>
                            <div class="infobox-footer">
                                <div class="infobox-content"><?php echo JText::_("COM_IJOOMLA_SEO_MISSING_DESCRIPTIONS"); ?></div>
                            </div>
                        </div>
                    </div>
                    
                </div><!--// end g_inner_shell-->
            </div><!--// end g_middle_shell-->
        </div>
        <!-- end basic info button -->
        
        <div class="row-fluid">
        	<div class="span12">
            	<div class="pull-right" id="menu-items" style="display:none;">
            		<?php
						$menus = $this->getAllMenus();
					?>
					<select name="menu_items" id="menu_items_id" style="margin-left:15px !important;" onchange="javascript:changeSource(document.getElementById('source').value);">
						<?php
							if(isset($menus) && count($menus) > 0){
								foreach($menus as $key=>$menu){
									echo '<option value="'.$menu["menutype"].'">'.$menu["title"].'</option>';
								}
							}
						?>
                    </select>
                </div>
                
                <div class="pull-right" id="jomsocial" style="display:none;">
            		<select name="jomsocial" id="jomsocial_id" style="margin-left:15px !important;" onchange="javascript:changeSource(document.getElementById('source').value);">
                    	<option value="0"><?php echo JText::_("COM_IJOOMLA_SEO_SELECT"); ?></option>
                        <option value="1"> Groups </option>
                        <option value="2"> Events </option>
                        <option value="3"> Photo Albums </option>
                        <option value="4"> Videos </option>
                        <option value="5"> Photos </option>
                    </select>
                </div>
                
                <div class="pull-right" id="zoo" style="display:none;">
            		<select name="zoo" id="zoo_id" style="margin-left:15px !important;" onchange="javascript:changeSource(document.getElementById('source').value);">
                    	<option value="0"><?php echo JText::_("COM_IJOOMLA_SEO_SELECT"); ?></option>
                        <option value="1"><?php echo JText::_("COM_IJOOMLA_SEO_ITEMS"); ?></option>
                        <option value="2"><?php echo JText::_("COM_IJOOMLA_SEO_CATEGORIES"); ?></option>
                    </select>
                </div>
                
                <div class="pull-right" id="k2" style="display:none;">
            		<select name="k2" id="k2_id" style="margin-left:15px !important;" onchange="javascript:changeSource(document.getElementById('source').value);">
                    	<option value="0"><?php echo JText::_("COM_IJOOMLA_SEO_SELECT"); ?></option>
                        <option value="1"><?php echo JText::_("COM_IJOOMLA_SEO_ITEMS"); ?></option>
                        <option value="2"><?php echo JText::_("COM_IJOOMLA_SEO_CATEGORIES"); ?></option>
                    </select>
                </div>
                
                <div class="pull-right" id="easy-blog" style="display:none;">
            		<select name="easy_blog" id="easy_blog_id" style="margin-left:15px !important;" onchange="javascript:changeSource(document.getElementById('source').value);">
                    	<option value="0"><?php echo JText::_("COM_IJOOMLA_SEO_SELECT"); ?></option>
                        <option value="1"><?php echo JText::_("COM_IJOOMLA_SEO_ITEMS"); ?></option>
                        <option value="2"><?php echo JText::_("COM_IJOOMLA_SEO_CATEGORIES"); ?></option>
                    </select>
                </div>
                
                <div class="pull-right">
            		<select name="source" id="source" onchange="javascript:changeSource(this.value);">
                    	<option value="0"> <?php echo JTExt::_("COM_IJOOMLA_SEO_SELECT_SOURCE"); ?> </option>
                        <option value="1"> Articles </option>
                        <option value="2"> Menu Items </option>
                        <option value="3"> JomSocial </option>
                        <option value="4"> Zoo </option>
                        <option value="5"> K2 </option>
                        <option value="6"> Kunena </option>
                        <option value="7"> EasyBlog </option>
                    </select>
                </div>
                
            </div>
        </div>
        
        <div id="ij-com-graph" class="row-fluid ij-com-graph">
            <div id="key-performance" class="graph-1 key-performance">
                <h2 class="ij-mod-title"><?php echo JText::_("COM_IJOOMLA_SEO_KEY_PERFORMANCE"); ?></h2>
                <div class="ij-graph-box row-fluid">
                    
                    <div id="g_daily_chart" class="row-flow">
                        <div class="span12">
                            <div id="content">
                                <div class="demo-container">
                                    <div class="ij-graph-options">
                                        <div>
                                            <input type="checkbox" checked="checked" onchange="javascript:changeDiagram();" value="up" name="ij-graf-up" id="ij-graf-up"><span>Up</span>
                                        </div>
                                        
                                        <div>
                                            <input type="checkbox" checked="checked" onchange="javascript:changeDiagram();" value="down" name="ij-graf-down" id="ij-graf-down"><span>Down</span>
                                        </div>
                                        
                                        <div>
                                            <input type="checkbox" checked="checked" onchange="javascript:changeDiagram();" value="same" name="ij-graf-same" id="ij-graf-same"><span>Same</span>
                                        </div>
                                    </div>
                                    <div id="placeholder" class="demo-placeholder">
                                    	
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div><!--end graph one-->
        </div>

    </div>

</div>

<div class="span4">

    <div class="span12">

        <?php

        $extensions = get_loaded_extensions();

        $text = "";

        if(in_array("curl", $extensions)){

            $data = "http://www.ijoomla.com/seo_announcements.txt";

            $ch = curl_init($data);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_TIMEOUT, 20);

            $text = curl_exec($ch);

        }

        else{

            $text = file_get_contents('http://www.ijoomla.com/seo_announcements.txt');

        }

        if($text && (trim($text) != '')){

            echo '<div class="well well-small" style="font-size:12px !important;">'.$text.'</div>' ;

        }

        ?>

    </div>

    <div class="clearfix"></div>

    <div class="row-flow">

        <div class="span12">

            <div id="ijoomla_news_tabs">

            </div>

        </div>

    </div>

</div>

</div>

<div class="clearfix"></div>

<div id="video-mistakes" class="row-fluid">
	<div class="span12">
        <div class="widget-header widget-header-flat">
        	<h5>
            	<i class="icon-magnet"></i>
                <?php echo JText::_("COM_IJOOMLA_SEO_MISTAKES"); ?>
			</h5>
        </div>                
        <div class="widget-body">
        	<div class="row-fluid">
                <div class="span6">
                    <?php
                    	$video_details = $this->getVideoDetails("ALgteesWtAI");
					?>
                    <div class="media">
						<a class="modal pull-left" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=ALgteesWtAI" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
                        	<img src="<?php echo $video_details["img"]; ?>" class="pull-left" alt="" title="" />
						</a>
						<div class="media-body">
                        	<a class="modal" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=ALgteesWtAI" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
								<?php echo $video_details["title"]; ?>
							</a>
                            <div class="media-content">
								<?php echo $video_details["content"]; ?>
                            </div>
                      	</div>
                    </div>
                </div>
                
                <div class="span6">
                    <?php
                    	$video_details = $this->getVideoDetails("tkMUFvPeHIs");
					?>
                    <div class="media">
						<a class="modal pull-left" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=tkMUFvPeHIs" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
                        	<img src="<?php echo $video_details["img"]; ?>" class="pull-left" alt="" title="" />
						</a>
						<div class="media-body">
                        	<a class="modal" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=tkMUFvPeHIs" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
								<?php echo $video_details["title"]; ?>
							</a>
                            <div class="media-content">
								<?php echo $video_details["content"]; ?>
                            </div>
                      	</div>
                    </div>
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span6">
                    <?php
                    	$video_details = $this->getVideoDetails("QWPvg502T3o");
					?>
                    <div class="media">
						<a class="modal pull-left" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=QWPvg502T3o" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
                        	<img src="<?php echo $video_details["img"]; ?>" class="pull-left" alt="" title="" />
						</a>
						<div class="media-body">
                        	<a class="modal" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=QWPvg502T3o" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
								<?php echo $video_details["title"]; ?>
							</a>
                            <div class="media-content">
								<?php echo $video_details["content"]; ?>
                            </div>
                      	</div>
                    </div>
                </div>
                
                <div class="span6">
                    <?php
                    	$video_details = $this->getVideoDetails("zpcLLxyzFyc");
					?>
                    <div class="media">
						<a class="modal pull-left" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=zpcLLxyzFyc" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
                        	<img src="<?php echo $video_details["img"]; ?>" class="pull-left" alt="" title="" />
						</a>
						<div class="media-body">
                        	<a class="modal" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=zpcLLxyzFyc" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
								<?php echo $video_details["title"]; ?>
							</a>
                            <div class="media-content">
								<?php echo $video_details["content"]; ?>
                            </div>
                      	</div>
                    </div>
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span6">
                    <?php
                    	$video_details = $this->getVideoDetails("aN2h_B87Sk0");
					?>
                    <div class="media">
						<a class="modal pull-left" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=aN2h_B87Sk0" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
                        	<img src="<?php echo $video_details["img"]; ?>" class="pull-left" alt="" title="" />
						</a>
						<div class="media-body">
                        	<a class="modal" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=aN2h_B87Sk0" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
								<?php echo $video_details["title"]; ?>
							</a>
                            <div class="media-content">
								<?php echo $video_details["content"]; ?>
                            </div>
                      	</div>
                    </div>
                </div>
                
                <div class="span6">
                    <?php
                    	$video_details = $this->getVideoDetails("q5oe5Uunzfc");
					?>
                    <div class="media">
						<a class="modal pull-left" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=q5oe5Uunzfc" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
                        	<img src="<?php echo $video_details["img"]; ?>" class="pull-left" alt="" title="" />
						</a>
						<div class="media-body">
                        	<a class="modal" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=q5oe5Uunzfc" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
								<?php echo $video_details["title"]; ?>
							</a>
                            <div class="media-content">
								<?php echo $video_details["content"]; ?>
                            </div>
                      	</div>
                    </div>
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span6">
                    <?php
                    	$video_details = $this->getVideoDetails("Thf_A89sXjg");
					?>
                    <div class="media">
						<a class="modal pull-left" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=Thf_A89sXjg" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
                        	<img src="<?php echo $video_details["img"]; ?>" class="pull-left" alt="" title="" />
						</a>
						<div class="media-body">
                        	<a class="modal" href="index.php?option=com_ijoomla_seo&amp;controller=about&amp;task=youtube&v=Thf_A89sXjg" rel="{handler: 'iframe', size: {x: 600, y: 550}}">
								<?php echo $video_details["title"]; ?>
							</a>
                            <div class="media-content">
								<?php echo $video_details["content"]; ?>
                            </div>
                      	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="alert-light" id="statistics-light" style="display:none;">
    <p style="font-size: 17px; font-weight: bold; text-align: justify;"><?php echo JText::_("COM_IJOOMLA_SEO_ONE_STATISTICS"); ?></p>
    <br />
    <br />
    <p class="pagination-centered">
        <form id="prform" name="prform" target="_blank" style="margin:0px; text-align: center;" method="post" action="http://www.ijoomla.com/index.php?option=com_digistore&controller=digistoreCart&task=add&pid[0]=94&cid[0]=94" onsubmit="return prodformsubmit4a60cb04c1341();">
            <input name="qty" value="1" type="hidden" />
            <input name="pid" id="product_id" value="94" type="hidden" />
            <input name="Button" type="submit" class="btn btn-warning" value="Buy Pro" />
        </form>
    </p>
    <br />
    <br />
    <p class="pagination-centered">
        <a href="http://seo.ijoomla.com/pricing/" style="font-size:18px; text-decoration:underline;" target="_blank"><?php echo JText::_("COM_IJOOMLA_SEO_COMPARE"); ?></a>
    </p>
    <p class="pagination-right">
        <a href="#" onclick="javascript:closePopUp('statistics-light'); return false;" style="font-size:14px;" >Close</a>
    </p>
</div>