<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<<?php echo "?";?>xml version="1.0"?>
<rsd version="1.0" xmlns="http://archipelago.phrasewise.com/rsd" >
    <service>
        <engineName>EasyBlog</engineName> 
        <engineLink><?php echo JURI::root();?></engineLink>
        <siteName><?php echo $title;?></siteName>
        <homePageLink><?php echo $link;?></homePageLink>
        <apis>>
            <api name="WordPress" blogID="1" preferred="true" apiLink="<?php echo $xmlrpc; ?>" />
            <api name="MetaWeblog" preferred="false" apiLink="<?php echo $xmlrpc;?>" blogID="1" />
        </apis>
    </service>
</rsd>