VLille Alert API
============

# Installation

## Installation en local

Récupérez le projet depuis github :

```shell
git clone git@github.com:ylly/vlille_alert.git
```

Installez [composer](https://getcomposer.org) :

```shell
curl -sS https://getcomposer.org/installer | php
```

Mettez à jour les librairies avec composer :

```shell
php composer.phar install
```

Configurez les permissions des répertoires du projet. Si vous êtes sur une machine Mac :

```shell
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo chmod +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs 
```

Sinon, il est recommandé d'utiliser les ACL comme suit :

```shell
HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs 
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs 
```

Configurer le vhost 

```shell
<VirtualHost *:80>
        ServerAdmin webmaster@vlillealert.fr
        ServerName vlillealert.local
        DocumentRoot /var/www/vlillealert.local/web
        DirectoryIndex app.php

        <Directory /var/www/vlillealert.local/web>
                Options Indexes ExecCGI FollowSymLinks
                Order allow,deny
                Allow from all
                AllowOverride all
        </Directory>

        ErrorLog /var/log/apache2/error-vlillealert.log
        LogLevel error
        CustomLog /var/log/apache2/access-vlillealert.log vhost_combined_time_end
</VirtualHost>
```

Activez le nouveau site 

```shell
sudo a2ensite /var/www/sites-available/vlille_alert.local
```

Ajouter une ligne dans le HOST pour la redirection DNS 

```shell
sudo echo "127.0.0.1      vlille_alert.local" >> /etc/hosts
```

Redémarrer Apache

```shell
sudo service apache2 reload
```

Vous pourrez maintenant accéder à l'API à l'adresse suivante :

http://vlillealert.local
