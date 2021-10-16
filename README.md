# Php development environment with docker-compose

Modern php 8 development environment with separate Nginx, php-fpm containers alongside with
one of the selected databases Postgresql or Mariadb, Mailhog for SMTP tests, Adminer for visual database UI, 
Rebrow to see Redis keys. 
* Please note that sessions configured to store on Redis, not a File. 
* XDebug 3 is enabled by default and integrated with Visual Studio Code.
* https is set for localhost tests with fake ssl cert.

#

DEAR DEVELOPER, PLEASE **READ THE ALL** INSTRUCTIONS BEFORE YELLING it is tl;dr** 

#

## Steps for setting up the development environment: #

- Before starting, enter the following lines at the bottom of the ``/etc/hosts`` file.
- If you'll need to communicate outside ports of multiple containers, please use the private ip address provided by your NIC. i.e. 192.168.1.xx

```
127.0.0.1 kunthar.localhost.com
127.0.0.1 k.localhost.com
```

- If you need to run more than one project multiple times, please *rename*  ```deploy``` folder to project name of the running project. 
- Also, if more than one application will run on the same machine, then it will be necessary to specify the ports of each application.
  For example, for two separate applications, app1 and app2, this repository will be cloned twice. deploy dir will be renamed to app1 and app2. 
  After the cloning process, you will definitely need to change the ports on one of them, because two separate applications to the tcp port at the same time CANNOT be bind!
* In all cases, first copy the ``env-sample``` file as a new file named ```.env``.
* Next, change the ports in this file if necessary. If only one application will run, you don't need to make any changes.
  Default incoming ports are as follows.

#

### Ports Used:

Service|Hostname|Port
-----|----------|-----------
WEBSERVER_HTTPS_PORT (app)|localhost|4433 (default) is redirected to kunthar.localhost.com
WEBSERVER_HTTP_PORT (app)|localhost|10000 (default)
MAILHOG_PORT Mailhog|mailhog|10001 (default)
MARIADB_PORT|mariadb|10002 (default)
POSTGRES_PORT|postgres|10003 (default)
ADMINER_PORT Adminer|localhost|10004 (default)
REDIS_UI_PORT Rebrow|localhost|10005 (default)
REDIS_PORT|redis|6379 (default)
php-fpm|php-fpm|9000 do not change this port!!! It is used by Nginx internally.

#

- Docker CE must be installed on your machine. It is recommended to use Ubuntu. See link for other operating systems.
  https://docs.docker.com/engine/install/ubuntu/
- Docker-compose must be installed on your machine. https://docs.docker.com/compose/install/
- The database is set to Postgresql by default. If you are going to use mariadb, in docker-compose.yml
  Comment the block that starts with postgres: with # and remove the comment from the mariadb: section.
- Under the app directory, the example index.php is located under Public. The application files should be placed under the ```app/``` directory.
- For packages that need to be installed with Composer. You need to copy the ``composer.json`` file to the ```app/``` directory.
  When docker-compose is run, it first tries to install packages from this file. Please docker-compose down and up when added new library to composer.json. This will automatically trigger the composer install process.
- First run is performed with ```docker-compose up``` command in shell or Visual Studio Code terminal.
- The images required for the first run will be taken from the internet and the php-fpm image will be built. This process will take time. Wait.
- If you want to make changes to the php.ini settings, the file ``deploy/phpdocker/php-fpm/php-ini-overrides.ini`` will be used.
- As long as the app directory is the application directory, you don't need to change the Nginx settings file.
- ```docker-compose up``` command will print logs from all running containers. Follow carefully.
- With the ```docker-compose up -d``` command when you are in a position to develop directly without making any adjustments.
  You can run it in the background. In this case, if you want to see the logs, use docker-compose logs.

#

## Development process

- The application redirects to the https port. Code change is automatically detected.
  * http://localhost:10000/
- Application https URL
  * https://kunthar.localhost.com:4433/
  * To continue Chrome, Brave, type this even if you don't see it on the error page: "This is unsafe"
- The application uses Mailhog for SMTP testing.
  * http://localhost:10001/
- Database visual interface.
  * http://localhost:10004/
  * server for postgres: postgres
  * server for mysql: mariadb
- Redis Interface
    * http://localhost:10005/
    * host: redis will be written. If the port has changed, write it down too.
    * To get key information without interface. ``docker exec -it deploy_redis_1 /usr/local/bin/redis-cli KEYS *```

# Application file permissions #
To set the correct file permissions inside the container:
`docker exec deploy_php-fpm_1 chown -R www-data:www-data /application/Public`


# Simple Xdebug settings.

## Xdebug 3
**Xdebug 3** settings are operated with the parameters defined in the ```php-fpm/php-ini-overrides.ini`` file.
#

- Install the following plugin in vscode. Felix Becker.
  * https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-debug
- Open the application's index.php file in Visual Studio Code.
- Select Add Configuration from the Run menu.
- Delete the contents of the resulting ``launch.json``` file and enter the following.


#
 ``launch.json```

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "stopOnEntry": true,
            "log": true,
            "pathMappings": {
                "/application": "${workspaceFolder}/app"
            },
        },
    ]
}
```

#

- Start with start debugging or F5 in the Run menu. Go to the app at localhost:10000. Xdebug will start working as soon as you set breakpoint.


## Docker compose tips: #

**Note:** Before running the commands, you must be in the directory where the docker-compose.yml file is located.

  * Start all containers in background: `docker-compose up -d`
  * Start all containers and track logs: `docker-compose up`. All container logs can be seen on this shell screen.
  * Stop containers: `docker-compose stop`
  * Kill containers (stops regardless of status): `docker-compose kill`
  * See container logs: `docker-compose logs`
  * Run command inside container: `docker-compose exec SERVICE_NAME COMMAND` where `COMMAND` is the command to run. Examples:
    * Shell access of PHP container, `docker-compose exec php-fpm bash`
    * Mysql shell access, `docker-compose exec mysql mysql -uroot -pCHOSEN_ROOT_PASSWORD`
    * Redis cli access, ``docker-compose exec -it redis /usr/local/bin/redis-cli```


