// <?php
use Drupal\menu_link_content\Entity\MenuLinkContent;

######################## Data area start #######################################
/**
 * The Top level menu name.
 * Example data structure :
 *
 * $menu_name = 'main';
 *
 * @var string
 */
$menu_name = '';

/**
 * Example data structure :
 *
 * $new_links[] = [
 *   'parent' => [
 *     'Parent-menu-title' => ['uri' => 'internal:/parent-menu-url'],
 *   ],
 *   'children' => [
 *     'sub-menu-1' => ['uri' => 'internal:/sub-menu-1-url'],
 *     'sub-menu-2' => ['uri' => 'internal:/sub-menu-2-url'],
 *     'sub-menu-3' => ['uri' => 'internal:/sub-menu-3-url'],
 *   ],
 * ];
 *
 * This data will create a parent menu "Parent-menu-title" with internal url
 * "/parent-menu-url" and three sub-menus under "main" Menu.
 *
 * @var array
 */
$new_links = [];

######################## Data area end #########################################

createMenu($new_links, $menu_name);


function createMenu($new_links, $menu_name) {
  if (!empty($new_links)) {
    $child_weight = 0;
    foreach ($new_links as $weight => $tree) {
      if (isset($tree['parent']) && !empty($tree['parent'])) {
        $isExpanded = isset($tree['children']) && !empty($tree['children']) ? 1 : 0;

        $menu_data = [
          'title'     => key($tree['parent']),
          'link'      => reset($tree['parent']),
          'menu_name' => $menu_name,
          'expanded'  => $isExpanded,
          'weight'    => $weight,
        ];

        $parent = createMenuLink($menu_data);
        $ptitle = $parent->getTitle();
        drush_print('Menu ' . $ptitle . 'created.');
        if (isset($tree['children']) && !empty($tree['children'])) {
          $pid = $parent->getPluginId();

          foreach ($tree['children'] as $child_title => $child_link) {
            $menu_data = [
              'title'     => $child_title,
              'link'      => $child_link,
              'menu_name' => $menu_name,
              'parent'    => $pid,
              'weight'    => $child_weight,
            ];
            $child = createMenuLink($menu_data);
            $child_weight++;
            drush_print('SubMenu ' . $ptitle . '>' . $child->getTitle() . ' created.');
          }
        }
      }
    }
  }
}

function createMenuLink($menu_data) {
  $menu = MenuLinkContent::create($menu_data);
  $menu->save();
  return $menu;
}
