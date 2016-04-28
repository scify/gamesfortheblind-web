<?php
/**
 * @package            JUserTube
 * @version            8.1
 * @author             Md. Afzal Hossain <afzal.csedu@gmail.com>
 * @link               http://www.srizon.com
 * @copyright          Copyright 2012 Md. Afzal Hossain All Rights Reserved
 * @license            http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
if (!class_exists('SrizonPagination')) {
	class SrizonPagination {
		private $_perPage;
		private $_instance;
		private $_page;
		private $_totalRows = 0;

		public function __construct($perPage, $instance) {
			$this->_instance = $instance;
			$this->_perPage = $perPage;
			$this->set_instance();
		}

		private function set_instance() {
			$this->_page = (int)(!isset($_GET[$this->_instance]) ? 1 : $_GET[$this->_instance]);
			$this->_page = ($this->_page == 0 ? 1 : $this->_page);
		}

		public function set_total($_totalRows) {
			$this->_totalRows = $_totalRows;
		}

		public function page_links($path = '?', $ext = null) {
			$adjacents = 2;
			$prev = $this->_page - 1;
			$next = $this->_page + 1;
			$lastpage = ceil($this->_totalRows / $this->_perPage);
			$lpm1 = $lastpage - 1;
			$pagination = "";
			if ($lastpage > 1) {
				$pagination .= "<ul class='srizon-pagination hidden-phone visible-desktop'>";
				if ($this->_page > 1)
					$pagination .= "<li><a href='" . $path . "$this->_instance=$prev" . "$ext'>".JText::_('JPREV')."</a></li>";
				else
					$pagination .= "<span class='disabled'>".JText::_('JPREV')."</span>";
				if ($lastpage < 7 + ($adjacents * 2)) {
					for ($counter = 1; $counter <= $lastpage; $counter++) {
						if ($counter == $this->_page)
							$pagination .= "<li><span class='current'>$counter</span></li>";
						else
							$pagination .= "<li><a href='" . $path . "$this->_instance=$counter" . "$ext'>$counter</a></li>";
					}
				} elseif ($lastpage > 5 + ($adjacents * 2)) {
					if ($this->_page < 1 + ($adjacents * 2)) {
						for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
							if ($counter == $this->_page)
								$pagination .= "<li><span class='current'>$counter</span></li>";
							else
								$pagination .= "<li><a href='" . $path . "$this->_instance=$counter" . "$ext'>$counter</a></li>";
						}
						$pagination .= "...";
						$pagination .= "<li><a href='" . $path . "$this->_instance=$lpm1" . "$ext'>$lpm1</a></li>";
						$pagination .= "<li><a href='" . $path . "$this->_instance=$lastpage" . "$ext'>$lastpage</a></li>";
					} elseif ($lastpage - ($adjacents * 2) > $this->_page && $this->_page > ($adjacents * 2)) {
						$pagination .= "<li><a href='" . $path . "$this->_instance=1" . "$ext'>1</a></li>";
						$pagination .= "<li><a href='" . $path . "$this->_instance=2" . "$ext'>2</a></li>";
						$pagination .= "...";
						for ($counter = $this->_page - $adjacents; $counter <= $this->_page + $adjacents; $counter++) {
							if ($counter == $this->_page)
								$pagination .= "<span class='current'>$counter</span>";
							else
								$pagination .= "<li><a href='" . $path . "$this->_instance=$counter" . "$ext'>$counter</a></li>";
						}
						$pagination .= "..";
						$pagination .= "<li><a href='" . $path . "$this->_instance=$lpm1" . "$ext'>$lpm1</a></li>";
						$pagination .= "<li><a href='" . $path . "$this->_instance=$lastpage" . "$ext'>$lastpage</a></li>";
					} else {
						$pagination .= "<li><a href='" . $path . "$this->_instance=1" . "$ext'>1</a></li>";
						$pagination .= "<li><a href='" . $path . "$this->_instance=2" . "$ext'>2</a></li>";
						$pagination .= "..";
						for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
							if ($counter == $this->_page)
								$pagination .= "<span class='current'>$counter</span>";
							else
								$pagination .= "<li><a href='" . $path . "$this->_instance=$counter" . "$ext'>$counter</a></li>";
						}
					}
				}
				if ($this->_page < $counter - 1)
					$pagination .= "<li><a href='" . $path . "$this->_instance=$next" . "$ext'>".JText::_('JNEXT')."</a></li>";
				else
					$pagination .= "<li><span class='disabled'>".JText::_('JNEXT')."</span></li>";
				$pagination .= "</ul>\n";
				// for small devices
				$pagination .= "<ul class='srizon-pagination hidden-desktop visible-phone'>";
				if ($this->_page > 1)
					$pagination .= "<li><a href='" . $path . "$this->_instance=$prev" . "$ext'>".JText::_('JPREV')."</a></li>";
				else
					$pagination .= "<span class='disabled'>".JText::_('JPREV')."</span>";
				if ($this->_page < $counter - 1)
					$pagination .= "<li><a href='" . $path . "$this->_instance=$next" . "$ext'>".JText::_('JNEXT')."</a></li>";
				else
					$pagination .= "<li><span class='disabled'>".JText::_('JNEXT')."</span></li>";
				$pagination .= "</ul>";
			}
			return $pagination;
		}
	}
}