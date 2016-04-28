<?php
 /**
 * Main Component File (Admin)
 *
 * @package			JUserTube 
 * @version			6.1.0
 *
 * @author			Md. Afzal Hossain <afzal.csedu@gmail.com>
 * @link			http://www.srizon.com
 * @copyright		Copyright 2012 Md. Afzal Hossain All Rights Reserved
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JToolbarHelper::title('JUserTube');
JToolbarHelper::preferences('com_jusertube');
JToolbarHelper::help( null, false, 'http://www.srizon.com/jusertube-documentation');
?>
<h2>You can set up the extension as a Module or Menu Item (component)</h2>
<h3>Module Setup</h3>
<p>Go to Module manager, find the module 'JUserTube' and setup the parameters properly<br/><a
		href="index.php?option=com_modules&filter_module=mod_jusertube">This link</a> will take you directly to module
	manager showing only the JUserTube modules (filtered)</p>
<h3>Menu Item Setup</h3>
<p>Go to Menu manager and add a menu item of JUserTube type (2 layouts available)</p>
<h2>&nbsp;</h2>
<h2>Force Sync</h2>
<p>Normally it will auto sync after the time interval you set. However, you can force the sync process by deleting
	'JUserTube' cache.<br /> Go to <a href="index.php?option=com_cache">Clear Cache</a> page and select 'jusertube' and click on
	the delete button.
<h2>&nbsp;</h2>
<h2>Check For New Version:</h2>
<p>You're using version 8.1 Go to <a target="_blank"
									   href="http://extensions.joomla.org/extensions/social-web/social-media/video-channels/16316">Joomla
		Extension Directory</a> to check if there's a newer version.</p>
