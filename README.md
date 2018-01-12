# Drush-Shell-Scripts
Drupal 8 Drush Shell Scripts

Use script for local site:

  - Put scripts in drupal's docroot:

    drush @my-alias scr php-script/{script name}

  - Put scripts anywhere outside docroot:

    cat php-script/{script name} | drush @my-alias scr -

Use script for remote site:

    cat php-script/{script name} | drush @remote-alias scr -


# More scripts Continuous update.
