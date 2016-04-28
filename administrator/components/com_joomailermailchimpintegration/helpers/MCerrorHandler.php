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

class MCerrorHandler {
	public static function getErrorMsg($MC) {
		$errorCodes = array('100', '101', '102', '103', '104', '105', '106',
							'120', '121', '122', '123', '124', '125', '126', '127',
							'200',
							'210', '211', '212', '213', '214', '215',
							'220', '221', '222',
							'230', '231', '232', '233',
							'250', '251', '252', '253', '254', '255',
							'270', '271',
							'300', '301',
							'310', '311', '312', '313', '314', '315', '316', '317', '318',
							'330',
							'350', '351', '352', '353', '354', '355',
							'500', '501', '502', '503', '504', '505', '506', '507', '508',
							'550', '551', '552', '553', '554'
							);
		if (in_array($MC->errorCode, $errorCodes)) {
			switch($MC->errorCode) {
				// 100: User Related Errors
				case '100':
					$msg = JText::_('USER NOT FOUND IN LIST');
					break;

				case '101':
					$msg = JText::_('User_Disabled');
					break;

				case '102':
					$msg = JText::_('USER NOT FOUND IN LIST');
					break;

				case '103':
					$msg = JText::_('User_NotApproved');
					break;

				case '104':
					$msg = JText::_('JM_INVALID_API_KEY');
					break;

				case '105':
					$msg = JText::_('User_UnderMaintenance');
					break;

				case '106':
					$msg = JText::_('Invalid_AppKey');
					break;

				// 120: User - Action Related Errors
				case '120':
					$msg = JText::_('User_InvalidAction');
					break;

				case '121':
					$msg = JText::_('User_MissingEmail');
					break;

				case '122':
					$msg = JText::_('User_CannotSendCampaign');
					break;

				case '123':
					$msg = JText::_('User_MissingModuleOutbox');
					break;

				case '124':
					$msg = JText::_('User_ModuleAlreadyPurchased');
					break;

				case '125':
					$msg = JText::_('User_ModuleNotPurchased');
					break;

				case '126':
					$msg = JText::_('User_NotEnoughCredit');
					break;

				case '127':
					$msg = JText::_('MC_InvalidPayment');
					break;

				// 200: List Related Errors
				case '200':
					$msg = JText::_('List_DoesNotExist');
					break;

				// 210: List - Basic Actions
				case '210':
					$msg = JText::_('List_InvalidInterestFieldType');
					break;

				case '211':
					$msg = JText::_('List_InvalidOption');
					break;

				case '212':
					$msg = JText::_('JM_LIST_INVALIDUNSUBMEMBER');
					break;

				case '213':
					$msg = JText::_('List_InvalidBounceMember');
					break;

				case '214':
					$msg = JText::_('List_AlreadySubscribed');
					break;

				case '215':
					$msg = JText::_('List_NotSubscribed');
					break;

				// 220: List - Import Related
				case '220':
					$msg = JText::_('List_InvalidImport');
					break;

				case '221':
					$msg = JText::_('MC_PastedList_Duplicate');
					break;

				case '222':
					$msg = JText::_('MC_PastedList_InvalidImport');
					break;

				// 230: List - Email Related
				case '230':
					$msg = JText::_('Email_AlreadySubscribed');
					break;

				case '231':
					$msg = JText::_('Email_AlreadyUnsubscribed');
					break;

				case '232':
					$msg = JText::_('Email_NotExists');
					break;

				case '233':
					$msg = JText::_('Email_NotSubscribed');
					break;

				// 250: List - Merge Related
				case '250':
					$msg = JText::_('List_MergeFieldRequired');
					break;

				case '251':
					$msg = JText::_('List_CannotRemoveEmailMerge');
					break;

				case '252':
					$msg = JText::_('List_Merge_InvalidMergeID');
					break;

				case '253':
					$msg = JText::_('List_TooManyMergeFields');
					break;

				case '254':
					$msg = JText::_('List_InvalidMergeField');
					break;

				// 270: List - Interest Group Related
				case '270':
					$msg = JText::_('List_InvalidInterestGroup');
					break;

				case '271':
					$msg = JText::_('List_TooManyInterestGroups');
					break;

				// 300: Campaign Related Errors
				case '300':
					$msg = JText::_('INVALID CAMPAIGNID');
					break;

				case '301':
					$msg = JText::_('Campaign_StatsNotAvailable');
					break;

				// 310: Campaign - Option Related Errors
				case '310':
					$msg = JText::_('Campaign_InvalidAbsplit');
					break;

				case '311':
					$msg = JText::_('JM_CAMPAIGN_INVALIDCONTENT').' ('.JText::_('JM_ERROR').': '.$MC->errorMessage.')';
					break;

				case '312':
					$msg = JText::_('Campaign_InvalidOption');
					break;

				case '313':
					$msg = JText::_('Campaign_InvalidStatus');
					break;

				case '314':
					$msg = JText::_('Campaign_NotSaved');
					break;

				case '315':
					$msg = JText::_('Campaign_InvalidSegment');
					break;

				case '316':
					$msg = JText::_('Campaign_InvalidRss');
					break;

				case '317':
					$msg = JText::_('Campaign_InvalidAuto');
					break;

				case '318':
					$msg = JText::_('MC_ContentImport_InvalidArchive');
					break;

				// 330: Campaign - Ecomm Errors
				case '330':
					$msg = JText::_('Invalid_EcommOrder');
					break;

				// 350: Campaign - Absplit Related Errors
				case '350':
					$msg = JText::_('Absplit_UnknownError');
					break;

				case '351':
					$msg = JText::_('Absplit_UnknownSplitTest');
					break;

				case '352':
					$msg = JText::_('Absplit_UnknownTestType');
					break;

				case '353':
					$msg = JText::_('Absplit_UnknownWaitUnit');
					break;

				case '354':
					$msg = JText::_('Absplit_UnknownWinnerType');
					break;

				case '355':
					$msg = JText::_('Absplit_WinnerNotSelected');
					break;

				// 500: Generic Validation Errors
				case '500':
					$msg = JText::_('Invalid_Analytics');
					break;

				case '501':
					$msg = JText::_('Invalid_DateTime');
					break;

				case '502':
					$msg = JText::_('Invalid_Email');
					break;

				case '503':
					$msg = JText::_('Invalid_SendType');
					break;

				case '504':
					$msg = JText::_('Invalid_Template');
					break;

				case '505':
					$msg = JText::_('Invalid_TrackingOptions');
					break;

				case '506':
					$msg = JText::_('Invalid_Options');
					break;

				case '507':
					$msg = JText::_('Invalid_Folder');
					break;

				case '508':
					$msg = JText::_('Invalid_URL');
					break;

				// 550: Generic Unknown Errors
				case '550':
					$msg = JText::_('Module_Unknown');
					break;

				case '551':
					$msg = JText::_('MonthlyPlan_Unknown');
					break;

				case '552':
					$msg = JText::_('Order_TypeUnknown');
					break;

				case '553':
					$msg = JText::_('Invalid_PagingLimit');
					break;

				case '554':
					$msg = JText::_('Invalid_PagingStart');
					break;
			}
		} else {
			$msg = 'JM_' . str_replace(' ', '_', $MC->errorMessage);
		}

		return $msg;
	}
}
