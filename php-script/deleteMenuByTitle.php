// <?php
use Drupal\menu_link_content\Entity\MenuLinkContent;

######################## Data area start #######################################
/**
 * The Top level menu name.
 * Example data structure :
 *
 * $menu_name  = 'main';
 *
 * @var string
 */
$menu_name  = '';

/**
 * Example data structure :
 *
 * $menu_titles = [
 *   'Menu-Name-1',
 *   'Menu-Name-2',
 * ];
 *
 * This data will delete two menus "Menu-Name-1" and "Menu-Name-2"
 * under "main" menu.
 *
 * @var array
 */
$menu_titles = [];

######################## Data area end #########################################

foreach ($menu_titles as $title) {
  deleteMenu($title, $menu_name);
}

function deleteMenu($title, $menu_name) {
  $query = \Drupal::entityQuery('menu_link_content');
  $query->condition('menu_name', $menu_name);
  $query->condition('title', $title);

  $mlids = $query->execute();

  if (empty($mlids)) {
    drush_print('Menu with name : "' . $title . '" not exists.');
  }
  else {
    $mlid = reset($mlids);
    $menu = MenuLinkContent::load($mlid);
    $menu->delete();
    drush_print('Menu ' . $title . ' has been deleted.');
  }
}
