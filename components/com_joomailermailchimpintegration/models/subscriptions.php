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

class joomailermailchimpintegrationModelSubscriptions extends jmModel {

    public function getLists() {
        $cacheGroup = 'joomlamailerMisc';
        $cacheID = 'Lists';
        if (!$this->cache($cacheGroup)->get($cacheID, $cacheGroup)) {
            $data = $this->getMcObject()->lists();
            $this->cache($cacheGroup)->store(json_encode($data), $cacheID, $cacheGroup);
        }

        return json_decode($this->cache($cacheGroup)->get($cacheID, $cacheGroup), true);
    }

    public function getIsSubscribed($listid, $email) {
        $sub = $this->getMcObject()->listMemberInfo($listid, $email);
        if (isset($sub['status']) && $sub['status'] == 'unsubscribed') {
            return false;
        }

        return $sub;
    }
}
