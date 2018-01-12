// <?php

######################## Data area start #######################################
/**
 * Example data structure :
 *
 * $vid = 'vocabulary_machine_name';
 *
 * @var string
 */
$vid = '';

/**
 * Example data structure :
 *
 * $term_names = [
 *   'Term-name-1',
 *   'Term-name-2',
 * ];
 *
 * This data will delete two terms under the vocabulary $vid.
 *
 * @var array
 */
$term_names = [];

######################## Data area end #########################################

deleteTerm($term_names, $vid);

function deleteTerm($term_names, $vid) {
  $term_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');

  $query = $term_storage->getQuery();

  $query
    ->condition('vid', $vid)
    ->condition('name', $term_names, 'in');
  $tids = $query->execute();

  if (empty($tids)) {
    drush_print('No terms under the vocabulary : ' . $vid);
  }
  else {
    $terms = $term_storage->loadMultiple($tids);
    $term_storage->delete($terms);
    drush_print('The terms "' . implode(',', $term_names) . '" under ' . $vid . ' has been deleted.');
  }
}
