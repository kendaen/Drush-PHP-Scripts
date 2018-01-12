// <?php
use Drupal\menu_link_content\Entity\MenuLinkContent;


################## Config Area Start ###########################################
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
 * Example structure:
 *
 * $trees[] = [
 *   'parent' => [
 *     // The origin is array as if not sure the actually name,
 *     // e.g. 'menu-name' or 'name-menu'
 *     'origin' => ['menu-name', 'name-menu'],
 *     'new'    => [
 *       'title'    => 'New-menu-name',
 *       'link'     => ['uri' => 'internal:/'],
 *       'expanded' => '',
 *     ],
 *   ],
 *   'add' => [
 *     '' => ['uri' => 'internal:/'],
 *   ],
 *   'update' => [
 *     [
 *       'origin' => [''],
 *       'new'    => [
 *         'title' => '',
 *         'link'  => ['uri' => 'internal:/'],
 *       ],
 *     ],
 *   ],
 * ];
 *
 * @var array
 */
$trees = [];

################### Config Area End ############################################

$parent = '';

foreach ($trees as $data) {
  // Find parent menu.
  $parent = findMenu($data['parent']['origin'], $menu_name);
  if (!$parent) {
    return;
  }
  elseif (!empty(array_values($data['parent']['new']))) {
    $parent = updateMenu($parent, $data['parent']['new']);
  }

  // Update menus if exists.
  if (isset($data['update']) && !empty($data['update'])) {
    foreach ($data['update'] as $link) {
      $origin = findMenu($link['origin'], $menu_name, $parent->getPluginId());
      if (!$origin) {
        continue;
      }
      updateMenu($origin, $link['new']);
    }
  }

  // Add new menu links if not exists.
  if (isset($data['add']) && !empty($data['add'])) {
    foreach ($data['add'] as $title => $link) {
      $menu_data = [
        'title'     => $title,
        'link'      => $link,
        'parent'    => $parent->getPluginId(),
        'menu_name' => $menu_name,
      ];
      createMenu($menu_data);
    }
  }
}

/**
 * Find menu by specify menu title, and optional parent id.
 */
function findMenu($menu_titles, $menu_name, $parent_id = NULL) {
  $menu_titles = (array) $menu_titles;
  $menu = '';
  foreach ($menu_titles as $title) {
    $query = \Drupal::entityQuery('menu_link_content');
    $query->condition('menu_name', $menu_name);
    $query->condition('title', $title);
    if (isset($parent)) {
      $query->condition('parent', $parent_id);
    }
    $mlids = $query->execute();
    if (!empty($mlids)) {
      $mlid = reset($mlids);
      $menu = MenuLinkContent::load($mlid);
      break;
    }
  }
  if (!$menu) {
    drush_print('Menu like: "' . implode(' ', $menu_titles) . '" not exists.');
  }

  return $menu;
}

/**
 * Update menu.
 */
function updateMenu($menu, $new_data) {
  $new_data = array_merge(['enabled' => 1], $new_data);
  foreach ($new_data as $field => $value) {
    if (!empty($value)) {
      $menu->set($field, $value);
    }
  }
  $menu->save();
  drush_print('The menu "' . $menu->getTitle() . '" is updated.');
  return $menu;
}

/**
 * Create new menu if not exists.
 */
function createMenu($menu_data) {
  $menu_data = array_merge(['enabled' => 1], $menu_data);
  if (findMenu($menu_data['title'], $menu_data['menu_name'], $menu_data['parent'])) {
    drush_print('The menu "' . $menu_data['title'] . '" is existing.');
    return;
  }
  $menu = MenuLinkContent::create($menu_data);
  $menu->save();
  drush_print('The menu "' . $menu->getTitle() . '" is created.');
  return $menu;
}
