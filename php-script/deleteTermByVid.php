// <?php

######################## Data area start #######################################
/**
 * Example data structure :
 *
 * $vid = [
 *   'vocabulary_machine_name_1',
 *   'vocabulary_machine_name_2',
 * ];
 *
 * This data will delete all terms under these two vocabularies.
 *
 * @var array
 */
$vids = [];
######################## Data area end #########################################

deleteTerms($vids);

function deleteTerms($vids) {
  $term_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $query = $term_storage->getQuery();
  $query->condition('vid', $vids, 'in');
  $tids = $query->execute();
  if (empty($tids)) {
    drush_print('No terms under the vocabulary : ' . implode(' , ', $vids));
  }
  else {
    $terms = $term_storage->loadMultiple($tids);
    $term_storage->delete($terms);
    drush_print('The terms under ' . implode(' , ', $vids) . ' have been deleted.');
  }
}
