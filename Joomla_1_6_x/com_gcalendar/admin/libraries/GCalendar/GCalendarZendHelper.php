<?php
/**
 * GCalendar is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * GCalendar is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GCalendar.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Allon Moritz
 * @copyright 2007-2011 Allon Moritz
 * @since 2.2.0
 */

class GCalendarZendHelper{

	public static function getEvents($calendar, $startDate = null, $endDate = null, $max = 1000){
		$cache = & JFactory::getCache('com_gcalendar');
		$cache->setCaching(GCalendarUtil::getComponentParameter('gc_cache', 1) == '1');
		if(GCalendarUtil::getComponentParameter('gc_cache', 1) == 2){
			$conf =& JFactory::getConfig();
			$cache->setCaching($conf->getValue( 'config.caching' ));
		}
		$cache->setLifeTime(GCalendarUtil::getComponentParameter('gc_cache_time', 900));

		return $cache->call( array( 'GCalendarZendHelper', 'internalGetEvents' ), $calendar, $startDate, $endDate, $max);
	}

	public static function internalGetEvents($calendar, $startDate = null, $endDate = null, $max = 1000){
		GCalendarZendHelper::loadZendClasses();
		
		$client = new Zend_Http_Client();
		$service = new Zend_Gdata_Calendar($client);

		$query = $service->newEventQuery();
		$query->setUser($calendar->calendar_id);
		if($calendar->magic_cookie != null){
			$query->setVisibility('private-'.$calendar->magic_cookie);
		}
		$query->setProjection('full');
		$query->setOrderBy('starttime');
		$query->setSortOrder('ascending');
		$query->setSingleEvents('true');
		if($startDate != null){
			$query->setStartMin(strftime('%Y-%m-%dT%H:%M:%S', $startDate));
		}
		if($endDate != null){
			$query->setStartMax(strftime('%Y-%m-%dT%H:%M:%S',$endDate));
		}
		if($startDate == null && $endDate == null){
			$query->setFutureEvents('false');
		}
		
		$query->setMaxResults($max);
		$timezone = GCalendarUtil::getComponentParameter('timezone');
		if(!empty($timezone)){
			$query->setParam('ctz', $timezone);
		}
		$query->setParam('hl', GCalendarUtil::getFrLanguage());

		try {
			$feed = $service->getFeed($query->getQueryUrl(), 'GCalendar_Feed');
			$feed->setParam('gcid', $calendar->id);
			$feed->setParam('gccolor', $calendar->color);
			$feed->setParam('gcname', $calendar->name);
			foreach ($feed as $event) {
				$event->setFeed($feed);
			}
			return $feed;
		} catch (Zend_Gdata_App_Exception $e) {
			JError::raiseWarning(200, $e->getMessage());
			return null;
		}
	}

	public static function loadZendClasses() {
		static $zendLoaded;
		if($zendLoaded == null){
			$mainframe = &JFactory::getApplication();
			$absolute_path = $mainframe->getCfg( 'absolute_path' );
			ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_gcalendar' . DS . 'libraries');

			require_once('Zend' . DS . 'Loader.php');
			Zend_Loader::loadClass('Zend_Gdata_AuthSub');
			Zend_Loader::loadClass('Zend_Gdata_HttpClient');
			Zend_Loader::loadClass('Zend_Gdata_Calendar');
			Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
			Zend_Loader::loadClass('GCalendar_Entry');
			$zendLoaded = true;
		}
	}
}
?>