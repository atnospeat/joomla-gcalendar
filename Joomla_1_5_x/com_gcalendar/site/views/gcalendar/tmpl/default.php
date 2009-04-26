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
 * @version $Revision: 2.1.0 $
 */

defined('_JEXEC') or die('Restricted access');
?>
<div
	class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php
$config = array(
 'showToolbar'=>'yes',
 'shortDayNames'=>'no',
 'defaultView'=>'month',
 'showEventTitle'=>'yes',
 'printDayLink'=>'yes',
 'cellHeight=90');
$model = &$this->getModel();
$cal = new GCalendar($model, $config);
$cal->display();
?>
</div>