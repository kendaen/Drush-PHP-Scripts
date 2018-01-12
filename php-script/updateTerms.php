// <?php

######################## Data area start #######################################
/**
 * Example data structure :
 *
 * $data[] = [
 *   'vid' => 'vocabulary_machine_name',
 *   'data' => [
 *     'field_machine_name_1' => 'field-new-value-1',
 *     'field_machine_name_2' => 'field-new-value-2',
 *   ],
 * ];
 *
 * This data will update all terms under vocabulary_machine_name with these two
 * fields.
 *
 * @var array
 */
$data=[];

######################## Data area end #########################################

$term_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');

foreach ($data as $group) {
  $vid = $group['vid'];
  $query = $term_storage->getQuery();
  $query->condition('vid', $vid);
  $tids = $query->execute();
  if (empty($tids)) {
    drush_set_error('No terms under the vocabulary : ' . $vid);
  }
  else {
    $terms = $term_storage->loadMultiple($tids);

    foreach ($terms as $term) {

      foreach ($group['data'] as $field => $value) {
        if ($term->hasField($field)) {
          $term->set($field, $value);
        }
      }
      $term->save();
      drush_print('Term ID: ' . $term->id() . ' Completed.');
    }
    drush_print($vid . ' Completed.');
  }
}

