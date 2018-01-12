// <?php

######################## Data area start #######################################
/**
 * Example data structure:
 *
 * $blocks[] = [
 *   'uuid' => '86d206c9-d526-4631-8ea0-9216f03aec2b',
 *   'info' => 'Block new title',
 *   'body' => [
 *     'value' => 'Block body new content',
 *   ],
 * ];
 *
 * This data will update the block which
 * UUID is "86d206c9-d526-4631-8ea0-9216f03aec2b", and update title to
 * "Block new title", update content to "Block body new content" with
 * "full_html" text format.
 *
 * @var array
 */
$blocks = [];

######################## Data area end #########################################

foreach ($blocks as $block) {
  block_update($block);
}

/**
 * Update a block.
 */
function block_update($data) {

  if (!notEmpty($data['uuid'])) {
    drush_print('Error : UUID not set.');
    return FALSE;
  }

  $block = \Drupal::service('entity.repository')->loadEntityByUuid('block_content', $data['uuid']);

  if (!$block) {
    drush_set_error('The block ' . $data['uuid'] . ' not exists.');
    return;
  }

  if (notEmpty($data['info'])) {
    $block->setInfo($data['info']);
  }

  if (notEmpty($data['body']['value'])) {
    $format = notEmpty($data['body']['format']) ? $data['body']['format'] : 'full_html';
    $block->body->value = $data['body']['value'];
    $block->body->format = $format;
  }

  $block->save();
  drush_print('The block ' . $data['uuid'] . ' has been updated.');
}

function notEmpty($var) {
  return isset($var) && !empty($var);
}
