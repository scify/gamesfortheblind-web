<link rel="stylesheet" href="<?php echo JURI::root() . 'media/com_joomailermailchimpintegration/backend/css/share.css';?>"/>

<div id="content"><h3><?php echo JText::_('JM_SHARE_ON_SOCIAL_NETWORKS');?></h3>
    <ul id="social-links">
        <li>
        <a class="twitter-button social-button" title="Twitter" href="http://twitter.com/home/?status=<?php echo $this->title.' - '.$this->url; ?>" target="_blank">Twitter</a>
        <li>
            <a class="facebook-button social-button" title="Facebook" href="http://www.facebook.com/share.php?u=<?php echo $this->url; ?>&t=<?php echo $this->title; ?>" target="_blank">Facebook</a>
        </li>
        <li>
            <a class="myspace-button social-button" title="MySpace" href="http://www.myspace.com/Modules/PostTo/Pages/?u=<?php echo $this->url; ?>&t=<?php echo $this->title; ?>" target="_blank">MySpace</a>
        </li>
        <li>
            <a class="digg-button social-button" title="Digg" href="http://digg.com/submit?phase=2&amp;url=<?php echo $this->url; ?>&title=<?php echo $this->title; ?>" target="_blank">Digg</a>
        </li>
        <li>
            <a class="delicious-button social-button" title="Delicious" href="http://del.icio.us/post?url=<?php echo $this->url; ?>title=<?php echo $this->title; ?>" target="_blank">Delicious</a>
        </li>
        <li>
            <a class="reddit-button social-button" title="reddit" href="http://reddit.com/submit?&amp;url=<?php echo $this->url; ?>title=<?php echo $this->title; ?>" target="_blank">reddit</a>
        </li>
    </ul>
</div>

