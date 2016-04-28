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
 * */

// no direct access
defined('_JEXEC') or die('Restricted Access');

require_once(JPATH_COMPONENT_ADMINISTRATOR . '/libraries/joomlamailer/hotActivityComposite.php');

class joomailermailchimpintegrationViewSubscriber extends jmView {

	public function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root(true) . '/media/com_joomailermailchimpintegration/backend/css/subscriber.css');

        JToolBarHelper::title(JText::_('JM_NEWSLETTER_SUBSCRIBERS'), $this->getPageTitleClass());

		$mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option . '.limitstart', 'limitstart', 0, 'int');

        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $MCapi = $params->get('params.MCapi');
        $JoomlamailerMC = new JoomlamailerMC();

		$AIM = true;
		$clientDetails = $this->getModel()->getClientDetails();

		if ($MCapi && $JoomlamailerMC->pingMC()) {
			JToolBarHelper::custom('goToLists', 'lists', 'lists', 'Lists', false, false);
			JToolBarHelper::spacer();
			if (JRequest::getVar('type') == 's') {
				JToolBarHelper::custom('unsubscribe', 'unsubscribe', 'unsubscribe', 'Unsubscribe', true, false);
				JToolBarHelper::spacer();
				JToolBarHelper::custom('delete', 'unsubscribe', 'unsubscribe', 'Delete', true, false);
				JToolBarHelper::spacer();
			} else if (JRequest::getVar('type') == 'u') {
				//JToolBarHelper::custom('resubscribe', 'resubscribe', 'resubscribe', 'Resubscribe', false, false);
			}
		}

		$userid = JRequest::getVar('uid', 0, 'get', 'string');
		$email = JRequest::getVar('email', 0, '', 'string', JREQUEST_ALLOWRAW);
		$memberInfo = $this->getModel()->getListsForEmail();
		foreach ($memberInfo['lists'] as $key => $list) {
			$member_rating = $memberInfo['lists'][$key]['member_rating'];
			break;
		}

		if ($userid) {
			$user = JFactory::getUser($userid);
		}
		//TODO convert $start to GMT using JConfig and tzoffset
		$start = $user->registerDate;

		//$listid = JRequest::getVar('listid', 0, 'get', 'string');
		//$campaigns = $this->getModel()->MC_object()->campaigns(array('sendtime_start'=>$start, 'list_id'=>$listid));
		$campaigns = $this->getModel()->getCampaignsSince($start);
		//$lists = $this->getModel()->MC_object()->listsForEmail($email);

		$stats = array();
		foreach ($campaigns as $campaign) {

			$listmemberinfo = $this->getModel()->getListMemberInfo($campaign['list_id'], $email);

			//Check if this email was ever subscribed to this list
			if ($listmemberinfo) {
				//if (in_array($campaign['list_id'], $lists)) {

				$clicks = 0;
				if ($AIM) {
					$clickStats = $this->getModel()->campaignEmailStatsAIM($campaign['id'], $user->email);
					if (isset($clickStats[0])) {
						foreach ($clickStats as $cs) {
							if ($cs['action'] == 'click') {
								$clicks++;
							}
						}
					}
				}

				$stats[$campaign['id']]['clicks'] = $clicks;

                $opens = $this->getModel()->campaignOpenedAIM($campaign['id']);

				if ($opens) {
					foreach ($opens as $o) {
						if ($o['email'] == $email) {
							$stats[$campaign['id']]['opens'] = $o['open_count'];
							$stats[$campaign['id']]['received'] = true;
						} else {
							$stats[$campaign['id']]['opens'] = 0;
							$softbounces = $this->getModel()->getSoftBounces($campaign['id']);
							$hardbounces = $this->getModel()->getHardBounces($campaign['id']);
							$bounces = array_merge($softbounces, $hardbounces);
							$stats[$campaign['id']]['received'] = in_array($email, $bounces) ? 0 : 1;
						}
					}
				}

				$stats[$campaign['id']]['title'] = $campaign['title'];
				$stats[$campaign['id']]['date'] = $campaign['send_time'];
				$stats[$campaign['id']]['segment_text'] = $campaign['segment_text'];
				$stats[$campaign['id']]['list_sub'] = $listmemberinfo['timestamp'];
			}
		}

		$cbpath = JPATH_ADMINISTRATOR . '/components/com_comprofiler/admin.comprofiler.php';
		$jspath = JPATH_ADMINISTRATOR . '/components/com_community/admin.community.php';
		$db = JFactory::getDBO();
		$avatar = JURI::root() . 'media/com_joomailermailchimpintegration/backend/images/mailchimp_avatar.jpg';
		$gravatar_default = $avatar;
		if (JFile::exists($cbpath)) {
			//community builder is being used
			$query = 'SELECT avatar FROM #__comprofiler WHERE id=' . $userid;
			$db->setQuery($query);
			$avatarPath = $db->loadResult();
			if ($avatarPath) {
				$avatar = JURI::root() . 'images/comprofiler/' . $avatarPath;
			}
		} else if (JFile::exists($jspath)) {
			//jomsocial is being used
			$query = 'SELECT avatar FROM #__community_users WHERE userid=' . $userid;
			$db->setQuery($query);
			$avatarPath = $db->loadResult();
			if ($avatarPath) {
				$avatar = JURI::root() . $avatarPath;
			}
		}

		if ($gravatar_default == $avatar) {
			$avatar = $this->getModel()->getGravatar($gravatar_default);
		}

		$twitterName = $this->getModel()->getTwitterName();
		$kloutScore = $this->getModel()->getKloutScore();
		$this->assignRef('kloutScore', $kloutScore);
		$this->assignRef('twitterName', $twitterName);

		$facebookName = $this->getModel()->getFacebookName();
		$this->assignRef('facebookName', $facebookName);

		$composite = new hotActivityComposite();

		$hotActivity = $composite->getActivity();
		$hotnessRating = $composite->getHotnessValue();
		$this->assignRef('hotnessRating', $hotnessRating);
		$this->assignRef('hotActivity', $hotActivity);

		$jomSocialGroups = $this->getModel()->getJomSocialGroups();

		$totalDiscussionsOfUser = $this->getModel()->getTotalJomSocialDiscussionsOfUser();
		$jomSocialDiscussions = $this->getModel()->getRecentJomSocialDiscussions();


		jimport('joomla.html.pagination');
		$pagination = new JPagination(count($stats), $limitstart, $limit);

		$this->assignRef('memberRating', $member_rating);
		$this->assignRef('jomSocialGroups', $jomSocialGroups);
		$this->assignRef('jomSocialDiscussions', $jomSocialDiscussions);
		$this->assignRef('totalDiscussionsOfUser', $totalDiscussionsOfUser);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('email', $email);
		$this->assignRef('stats', $stats);
		$this->assignRef('limitstart', $limitstart);
		$this->assignRef('subscribed', $subscribed);
		$this->assignRef('user', $user);
		$this->assignRef('avatar', $avatar);

		parent::display($tpl);
		require_once(JPATH_COMPONENT . '/helpers/jmFooter.php');
	}
}
