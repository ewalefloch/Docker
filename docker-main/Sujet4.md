# Sujet 4 - SAE 1.03 - Générateur de graphique de Weblogs
## Contexte
Notre sujet de SAE concernait la génération d'un graphique à parir de Weblogs.

Son fonctionnement est très simple. On lui fournit en entrée (fichier **sample.apache**, **sample.json**, **sample.csv**) 3 fichiers contenant des logs d'utilisateur. Après 3 étapes, un fichier contenant une image d'un graphique ainsi qu'un fichier html sera présent.

Note: Le programme ne fonctionne pas en continue, et les résultats précédent seront écraser lors d'un relancement du programme !

De plus, si vous éxécuter le programme sur sur les ordinateurs de l'iut l'image dans le fichier ***start*** dervra être modifier !
***

## Etapes
- ***scriptGraphe*** : Conversion des différents fichiers sample et regroupement des données dans un unique fichier !
- ***SelData*** : Selection des données pour la génération d'un graphique.
- ***Gener*** : Génération d'un graphique et génération d'un fichier html

## Sources 
Il faudra peut-être **chmod +x** les scripts car Moodle aura peut-être modifier les droits initiaux.

## Configuration
Les fichiers **samples** contiennent un ensemble de logs qu'il faudra standardiser. Exemple des fichiers samples, qui fonctionne OK pour notre programme. 

***Fichier sample.apache***
```
54.36.148.92 - - [19/Dec/2020:14:16:44 +0100] "GET /index.php?option=com_phocagallery&view=category&id=2%3Awinterfotos&Itemid=53 HTTP/1.1" 200 30662 "-" "Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)" "-"

92.101.35.224 - - [19/Dec/2020:14:29:21 +0100] "GET /administrator/index.php HTTP/1.1" 200 4263 "" "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)" "-"
```
***Fichier sample.csv***
```
"157.48.153.185";"";"19-12-2020 14:08:08 +0100";"GET";"/favicon.ico";"HTTP/1.1";404;217;"http://www.almhuette-raith.at/apache-log/access.log";"Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36"

"216.244.66.230";"admin";"19-12-2020 14:14:26 +0100";"GET";"/robots.txt";"HTTP/1.1";200;304;"Mozilla/5.0 (compatible; DotBot/1.1; http://www.opensiteexplorer.org/dotbot, help@moz.com)"
```
***Fichier sample.json***
```
[
   {
      "date": "19/Dec/2020:13:57:26 +0100",
      "ip": "13.66.139.0",
      "user": null,
      "method": "GET",
      "request": "/index.php?option=com_phocagallery&view=category&id=1:almhuette-raith&Itemid=53",
      "protocol": "HTTP/1.1",
      "code": 200,
      "size": 32653,
      "referer": null,
      "agent": "Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)"
   },
   {
      "date": "19/Dec/2020:14:08:06 +0100",
      "ip": "157.48.153.185",
      "user": "admin",
      "method": "GET",
      "request": "/apache-log/access.log",
      "protocol": "HTTP/1.1",
      "code": 200,
      "size": 233,
      "referer": null,
      "agent": "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36"
   }
]
```
***
## Commentaire sur le code du script *start*
Ce script (**start**) met en place l'environnement de travail et lance un conteneur afin de lancer les scripts.

Le script va récupérer automatiquement l'images qui sera utiliser afin de généré le script !

Attention, dans ce script nous utiliseront l'image **bigpapoo/gnuplot**. Si vous vous trouver sur les ordinateurs de l'iut, il vous faudra modifier cette partie ci. En remplacant cette partie-ci du script
```
docker image pull bigpapoo/gnuplot
```
Par celle-ci :
```
docker image pull img_biag
```
Cette étape est a faire **avant** le lancement du script !

Le script start contient ceci :
- Création d'un volume (nommé **sae103**)

    Ce volume contiendra aussi les différent script. On utilisera donc une conteneur éphémère (nommé **sae103-tmp**).

    Ce conteneur n'a que seul utilité la copie des différents fichier dans le volume qui lui est bien entendu attaché.

```
docker volume create sae103
```
***
- Création du conteneur éphèmère (**sae103-tmp**) évoqué précédemment
```
docker container run -d --name sae103-tmp -v sae103:/data bigpapoo/gnuplot
```
***
- Copie des scripts et fichiers dans le volume (**sae103**) monté sur le conteneur éphémère.
```
docker cp gener sae103-tmp:/data
docker cp scriptApache.php sae103-tmp:/data
docker cp scriptJson.php sae103-tmp:/data
docker cp scriptGraphe sae103-tmp:/data
docker cp scriptCsv.php sae103-tmp:/data
docker cp SelData.php sae103-tmp:/data
docker cp sample.json sae103-tmp:/data
docker cp sample.apache sae103-tmp:/data
docker cp sample.csv sae103-tmp:/data
```
***
- Arrêt et suppression du conteneur éphémère. Le volume **sae103** n’est plus attaché à un conteneur mais il est préservé, ainsi que son contenu (les scripts).
```
docker container stop sae103-tmp
docker container rm sae103-tmp
```
***
- Lancement du conteneur *scriptGraphe*(nommé sae103-Graphe) qui contient l'étape 1 et 2 :

    - -ti : permet de rentrer dans le conteneur pour voir les différents étapes.
    - -v : Permet de lui attaché le volume pour qu'il y trouve son script, ainsi que ses fichiers de travail qui se générerons plus tard.
    - -w pour indiquer le dossier de travail du conteneur
```
docker container run -ti --name sae103-Graphe -w /data -v sae103:/data bigpapoo/gnuplot ./scriptGraphe
```
***
- Création d'un dossier **resultat** et récupération de l'image du graphique dans le dossier **resultat** :
```
mkdir resultat
docker container cp sae103-Graphe:/data/file.png ./resultat/file.png
```
***
- Génération du fichier **index.html** à partir de l'image récupéré dans le dossier résultat:
```
echo "<html lang=\"en\">" > resultat/index.html
echo "<head>" >> resultat/index.html
echo "<meta charset=\"UTF-8\">" >> resultat/index.html
echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">" >> resultat/index.html
echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">" >> resultat/index.html
echo "<title>Document</title>" >> resultat/index.html
echo "</head>" >> resultat/index.html
echo "<body>" >> resultat/index.html
echo "<h1>Sujet 4</h1>" >> resultat/index.html
echo "<p>Statistique sur les ip par rapport à la taille de la requête</p>" >> resultat/index.html
echo "<img src='file.png' alt="">" >> resultat/index.html
echo "</body>" >> resultat/index.html
echo "</html>" >> resultat/index.html
```
***
- Suppréssion du conteneur(sae103-Graphe) :
```
docker container stop sae103-Graphe
docker container rm sae103-Graphe
```
***
- Suppréssion du volume **sae103**
```
docker volume rm sae103
```
***
## Dépôt des fichiers de travail
Placer les fichiers de travail( les 3 fichier **sample**) les fichiers seront copier et envoyé dans l'étape de copie des fichiers et script depuis le fichier *start*.

## Récupération de la production
La récupération des fichiers finaux se fait également dans le fichier *start* avec l'étape de création d'un dossier **résultat** au préalable.

##  Résumé du processus complet
### Terminal 1
Lancer le fichier *start*
```
./start
```
Toute les étapes se font automatiquement et les différentes étapes seront affichés à l'écran.

Note: Une erreurs pourrait apparaitre si un dossier **resultat** éxiste déjà !
