<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
$class = $item->anchor_css ? 'class="' . $item->anchor_css . ($item->deeper ? ' dropdown-toggle" ' : '" ') : ($item->deeper ? 'class="dropdown-toggle" ' : ''); // << changed
$title = $item->anchor_title ? 'title="' . $item->anchor_title . '"' . ($item->deeper ? ' data-toggle="dropdown" ' : '') : ($item->deeper ? 'data-toggle="dropdown" ' : ''); // << changed

if ($item->menu_image)
{
	$item->params->get('menu_text', 1) ?
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="image-title">' . $item->title . '</span> ' :
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />';
}
else
{
	$linktype = $item->title . ($item->deeper ? '<span class="caret"></span>' : ''); // << changed
}

switch ($item->browserNav)
{
	default:
	case 0:
?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
		break;
	case 1:
		// _blank
?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" target="_blank" <?php echo $title; ?>><?php echo $linktype; ?></a><?php
		break;
	case 2:
	// window.open
?><a <?php echo $class; ?>href="<?php echo $item->flink; ?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;" <?php echo $title; ?>><?php echo $linktype; ?></a>
<?php
		break;
}

$menuCols = 6;

$menuColWith = 12 / $menuCols;


echo '<ul class="dropdown-menu" role="menu">';

	echo '<li><!-- Content container to add padding --><div class="yamm-content">';
		
		foreach ($item->subMenu as $key => $subItem) {
		
			// just top maunfacturer should be shown
			if($subItem->params->get('menu-anchor_css') == "hidden") {
			
			}
			else
			{
			
				if($key % $menuCols == 0) {
					echo '<div class="row">';
				}
				
				echo '<ul class="col-sm-' . $menuColWith . ' list-unstyled">';
				
				echo '<li><p><strong><a class="navbar-link" href="' . $subItem->flink . '">' . $subItem->title . '</a></strong></p></li>';
				
				foreach ($subItem->subMenu as $subSubItem) {
					
					// just top devices should be shown
					if($subSubItem->params->get('menu-anchor_css') == "hidden") {
						
					}
					else {
						$subSubTitle = $subSubItem->anchor_title ? 'title="' . $subSubItem->anchor_title . '"' : '';
						echo '<li><a class="navbar-link" href="' . $subSubItem->flink . '" ' . $subSubTitle . '>' . $subSubItem->title . '</a></li>';
					}
					
				}
				
				echo '</ul>';
				
				if(($key + 1) % $menuCols == 0) {
					echo '</div>';
					echo '<div class="row"><br /></div>';
				}
				
			}
		
		}		
			  
	echo '</div></li>';

echo '</ul>';
