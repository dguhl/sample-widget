Sample Widget
=============

How to setup:

1. Clone this repository in a prepared environment, now called devhost.tld.
2. Open http://devhost.tld/user/create and create a user entry.
3. Open http://devhost.tld/ . You shouldn't see anything.
4. Edit app/Resources/views/default/index.html.twig and change the block javascripts, use your uuid as js filename.
5. Open http://devhost.tld/ again. You see a blue widget on the bottom right.