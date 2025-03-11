dockers drupal-11 image is not suitable for setting up docker in drupal project. Because drupal:11 image downloads and setups drupal 11 in /opt/drupal and
symblink web folder to /var/www/html. 

We cannot mount drupal code base volume to host machine.

Hence I have created this repo.

In this repository we can use either lando or docker compose.

step 1:- 
clone the code . 

Step 2:- 
run below command to setup drupal 11 instance using docker compose.
```
$ docker compose up -d
```

run below command to setup drupal 11 instance using lando.

```
$ lando start
```


If docker :
access code base 
$ docker compose exec -it web /bin/bash 
access  database
$ docker compose exec -it db /bin/bash 

drush and composer are installed.  

