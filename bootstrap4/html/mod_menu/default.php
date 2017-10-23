<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
?>
<?php // The menu class is deprecated. Use nav instead. ?>
<ul class="nav navbar-nav<?php echo $class_sfx;?>"<?php
	$tag = '';

	if ($params->get('tag_id') != null)
	{
		$tag = $params->get('tag_id') . '';
		echo ' id="' . $tag . '"';
	}
?>>
<?php
$megaMenuArray = array();

$mainCategoryMenuMap = array();

$subCategoryMenuMap = array();

foreach ($list as $i => &$item) {

	$mainCategoryType = $item->params->get('main_category_type');

	if(isset($mainCategoryType)) {

		if($item->level == 1) {

			if(array_key_exists($mainCategoryType, $mainCategoryMenuMap)) {

				$mainCategoryKey = $mainCategoryMenuMap[$mainCategoryType];

				$megaMenuItem = $megaMenuArray[$mainCategoryKey];

			}
			else {

				$megaMenuItem = new stdClass;

				// required joomla attributes
				$megaMenuItem->type = 'yamm';
				$megaMenuItem->deeper = 1;
				$megaMenuItem->anchor_css = 'nav-link';
				$megaMenuItem->anchor_title = null;
				$megaMenuItem->menu_image = null;
				$megaMenuItem->browserNav = 0;
				$megaMenuItem->flink = '#';

				// specific attributes
				$megaMenuItem->mainCategoryType = $mainCategoryType;
				$megaMenuItem->title = $item->params->get('main_category_label');

				$megaMenuItem->subMenu = array();

			}

			$item->subMenu = array();

			// save key of sub category menu
			$subCategoryMenuMap[$mainCategoryType][$item->params->get('sub_category')] = count($megaMenuItem->subMenu);

			$megaMenuItem->subMenu[] = $item;


			if(!array_key_exists($mainCategoryType, $mainCategoryMenuMap)) {

				// save key of main category menu
				$mainCategoryMenuMap[$mainCategoryType] = count($megaMenuArray);

				$megaMenuArray[] = $megaMenuItem;

			}

		}
		else if($item->level == 2) {

			$mainCategoryKey = $mainCategoryMenuMap[$mainCategoryType];

			$megaMenuItem = $megaMenuArray[$mainCategoryKey];

			$subCategoryKey = $subCategoryMenuMap[$mainCategoryType][$item->params->get('sub_category')];

			$megaMenuItem->subMenu[$subCategoryKey]->subMenu[] = $item;

		}

	}
	else {

		// default joomla menu items were just copied

		$megaMenuArray[] = $item;
	}
}

//foreach ($list as $i => &$item) {
foreach ($megaMenuArray as $i => &$item)
{

	$class = '';

	if($item->type == 'yamm') {

		$class .= ' dropdown yamm-fw';

		$current = false;

		foreach ($item->subMenu as $subItem) {

			if ($subItem->id == $active_id) {
				$current = true;

				break;
			}

		}

		if ($current) {
			$class .= ' current';
		}

		$active = false;

		foreach ($item->subMenu as $subItem) {

			if (in_array($subItem->id, $path)) {
				$active = true;

				break;
			}

		}

		if ($active) {
			$class .= ' active';
		}

		if (!empty($class)) {
			$class = ' class="' . trim($class) . '"';
		}

		echo "<script>console.log('" . $class . "');</script>";

		echo '<li' . $class . '>';

		require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);

		echo '</li>';

	}
	else {

		$class = 'nav-item item-' . $item->id;

		if (($item->id == $active_id) OR ($item->type == 'alias' AND $item->params->get('aliasoptions') == $active_id))
		{
			$class .= ' current';
		}

		if (in_array($item->id, $path))
		{
			$class .= ' active';
		}
		elseif ($item->type == 'alias')
		{
			$aliasToId = $item->params->get('aliasoptions');

			if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
			{
				$class .= ' active';
			}
			elseif (in_array($aliasToId, $path))
			{
				$class .= ' alias-parent-active';
			}
		}

		if ($item->type == 'separator')
		{
			$class .= ' divider';
		}

		if ($item->deeper)
		{
			$class .= ' deeper';
		}

		if ($item->parent)
		{
			$class .= ' parent dropdown';
		}

		if (!empty($class))
		{
			$class = ' class="' . trim($class) . '"';
		}

		echo '<li' . $class . '>';
	    $item->anchor_css = 'nav-link';

		if($item->level > 1) {
			$item->anchor_css = 'dropdown-item';
		}

		// The next item is deeper.
		if ($item->deeper)
		{
			$item->anchor_css .= ' dropdown-toggle';
			$item->data = 'data-toggle="dropdown"';
		}

		// Render the menu item.
		switch ($item->type) :
			case 'separator':
			case 'url':
			case 'component':
			case 'heading':
				require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
				break;

			default:
				require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
				break;
		endswitch;

		// The next item is deeper.
		if ($item->deeper)
		{
			echo '<ul class="nav-child unstyled dropdown-menu">';
		}
		elseif ($item->shallower)
		{
			// The next item is shallower.
			echo '</li>';
			echo str_repeat('</ul></li>', $item->level_diff);
		}
		else
		{
			// The next item is on the same level.
			echo '</li>';
		}
	}
}
?></ul>
