###############################
-----------Informace-----------

Součástí přílohy jsou reporty v PDF testovaných serverů ve složce reporty_serveru
a zdrojové kódy webové aplikace ve složce nette-app.
Vytvořená virtuálka slouží pro odzkoušení webové aplikace.
Při práci ve virtuálce je potřeba používat "sudo" u uživatele app.
Příkazy vypnutí/restartu:
	$ sudo poweroff
	$ sudo reboot
###############################
-----Zprovoznění virtuálky-----

1. Stáhnout virtuálku Appserver z adresy:
	https://drive.google.com/file/d/15STmLAxDMifQQEIcFDGosYYN1Zme4-5I/view?usp=sharing
2. Extrahovat obsah.
3. Importovat do VMware a nastavit Network adapter na:
	Bridge - webová aplikace dostupná na hostovacím PC
		nebo
	NAT - webová palikace dostupná na jiných virtuálních strojích s grafickým prostředím
4. Přihlásit se (app) a zjistit IP adresu stroje příkazem:
	$ sudo ifconfig
5. Zadat IP adresu do prohlížeče podle nastavení v bodě č. 3.
-------------------------------
###############################
----------Přihlášení-----------

Login: app
Heslo: ppa

Login: root
Heslo: ppa

Je povolený SSH.
-------------------------------
###############################
------Adresáře virtuálky-------

Virtuální host:
	/etc/apache2/sites-available

Webová aplikace:
	/var/www/appserver/html/nette-app

Skripty:
	/var/www/appserver/html/nette-app/bin
-------------------------------
###############################
-----Povolení ladění Tracy-----
----------(volitelné)----------

Otevřít soubor:
	/var/www/appserver/html/nette-app/app/Bootstrap.php

Odkomentovat řádky:
	//$configurator->setDebugMode(true);
	//$configurator->enableTracy(__DIR__ . '/../log');
	


