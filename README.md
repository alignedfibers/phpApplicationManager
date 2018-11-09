- To get running use the database_dump to init tables + sample data

- root directory for app is hardcoded in bootstrap/runstatV3-2.php

- root directory is also hardcoded in htaccess

- mysql server settings are in bootstrap/configV3.php

- This is built to be served on apache2 with PHP 5.5.33-1~dotdeb+7.1 CLI

- It is running on debian at the moment

- For security purpose I do not keep this live

- A user authentication and environment builder is a seperate project

- You will need to add the environment builder from a previous version

- Warning - its a cool router / session / runid provider that provides
  runid on subdirectory bases and maintains visit history. but should 
  not be run live without first setting up authentication.

- When setting up you also need to create a cronjob that runs 
  bootstrap/sessionclean.php

There are some todos

- authenticate to redis or other key value safe to store scoped
  session values by session and runid, scoped to app directory
  that is binded to the provider directory

- Add circles library apps must define in dependencies.
  and set more accessability features after environment 
  creator is implimented

- It runs, I wish I could go back and look at all of the 
  previous edits on this one - it would be interesting to
  see exactly what I changed and remember why I was doing
  No playbacks on this one.


