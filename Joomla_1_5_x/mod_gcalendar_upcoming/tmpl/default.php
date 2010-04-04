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
 * @copyright 2007-2009 Allon Moritz
 * @version $Revision: 2.1.1 $
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

$event_display = $params->get('output', '');

$dateformat=$params->get('dateFormat', '%d.%m.%Y');
$timeformat=$params->get('timeFormat', '%H:%M');

echo $params->get( 'textbefore' );

for ($i = 0; $i < sizeof($gcalendar_data) && $i <$params->get( 'max', 5 ); $i++){
	$item = $gcalendar_data[$i];
	echo GCalendarUtil::renderEvent($item, $event_display, $dateformat, $timeformat);
}

echo $params->get( 'textafter' );
?>
