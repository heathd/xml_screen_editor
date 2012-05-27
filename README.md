XML Screen Editor
=================

Requirements:
  * PHP 5.3 (probably works with any 5.0+ version but not tested)
  * simplexml (included as standard with php 5.0+)

Purpose/usage
-------------

Allows editing of screen XML files with a particular format. 

Data files are stored in the `data` subdirectory.

Each time an edit is made, a backup version of the previous file is made before the new data is written to disk. Five backup files are kept in total (suffixed `.1`, `.2` ... `.5`).

Installation 
------------

You need to make sure the data directory is writable by the process running the web server (apache, or www-data normally).

    $ chgrp www-data data
    $ chmod g+w data

You also need to make sure the scenario_1.xml file is writable

    $ chgrp www-data data/scenario_1.xml
    $ chmod g+w data/scenario_1.xml

I also recommend setting up basic auth in your webserver. Consult your ISP's documentation or see: http://httpd.apache.org/docs/2.0/howto/auth.html

