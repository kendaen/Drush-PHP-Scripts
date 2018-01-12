// <?php
use Drupal\menu_link_content\Entity\MenuLinkContent;

######################## Data area start #######################################
/**
 * Example data structure :
 *
 * $menu_names = [
 *   'main',
 *   'footer',
 * ];
 *
 * This data will delete all sub menus under "main" and "footer".
 *
 * @var array
 */
$menu_names = [];

######################## Data area end #########################################

foreach ($menu_names as $menu_name) {
  deleteAllMenusUnder($menu_name);
}


function deleteAllMenusUnder($menu_name) {
  $menu_storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');

  $query = $menu_storage->getQuery();
  $query->condition('menu_name', $menu_name);
  $mids = $query->execute();

  if (empty($mids)) {
    drush_print('No sub-menu under the menu : ' . $menu_name);
  }
  else {
    $menus = $menu_storage->loadMultiple($mids);
    $menu_storage->delete($menus);
    drush_print('The sub-menus under ' . $menu_name . ' have been deleted.');
  }
}
