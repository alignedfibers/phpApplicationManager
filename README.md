- To get running use the database_dump to init tables + sample data

- root directory for app is hardcoded in bootstrap/runstatV3-2.php

- root directory is also hardcoded in htaccess

- mysql server settings are in bootstrap/configV3.php

- Environment is: Apache2/PHP 5.5.33-1~dotdeb+7.1 CLI/ Debian Linux

- A user authentication and environment builder is in a seperate project

- When setting up you also need to create a cronjob that runs 
  bootstrap/sessionclean.php

There are some todos

- authenticate to redis or other key value safe to store scoped
  session values by session and runid

- Add circles that library apps must define in dependencies.
  and set more accessability features after environment 
  creator is implimented

