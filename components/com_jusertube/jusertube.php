<?php
 /**
 * Main Component File
 *
 * @package			JUserTube 
 * @version			8.1
 *
 * @author			Md. Afzal Hossain <afzal.csedu@gmail.com>
 * @link			http://www.srizon.com
 * @copyright		Copyright 2012 Md. Afzal Hossain All Rights Reserved
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Require the base controller

require_once( JPATH_COMPONENT.'/controller.php' );
$controller   = new JusertubeController( );
$controller->execute( JRequest::getWord( 'task' ) );
$controller->redirect();
