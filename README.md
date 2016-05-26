Sample Widget
=============

How to setup:

1. Clone this repository in a prepared environment, now called devhost.tld, execute composer install.
2. To initialize the database, execute in shell: php bin/console doctrine:schema:generate
3. Open http://devhost.tld/user/create and create a user entry.
- OR -
3. Load included fixtures, with php bin/console doctrine:fixtures:load
4. Open http://devhost.tld/ . You shouldn't see anything special.
5. Edit app/Resources/views/default/index.html.twig and change the block "javascripts", use your chosen uuid as js filename.
- OR -
5. After migration, pick a uuid from /user and use it as a js filename in views/default/index.html.twig
6. Open http://devhost.tld/ again. You see a blue widget on the bottom right, with some rating from the chosen user.

The widget JS is empty if the uuid is invalid or does not exist. The site will still work, even if there is no script.
Of course, that would be different, if there'd be any frontend dependency on that widget...