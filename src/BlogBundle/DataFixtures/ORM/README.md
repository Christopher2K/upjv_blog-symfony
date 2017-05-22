# Les fixtures

Les fixtures sont des données statiques qui peuvent être créés dans le code et générée directement dans la BDD à l'aide 
du client en ligne de commande Symfony.

Elles permettent le test et le développement de features avec des mock datas (fausses données), afin de remplir certains
cas de tests particuliers.

Ici j'ai mis en places des fixtures pour permettre à chacun de balanacer ses données et pouvoir bosser avec. Libre à vous
d'en ajouter mais faites attention à l'ordre (car il y a un ordre). Dans le doute, demandez dans le groupe Facebook.

## Documenetation
[Lien pour la docu sur les fixtures](http://symfony.com/doc/current/doctrine/lifecycle_callbacks.html)

**Ligne de commande pour générer les données** : `php bin/console doctrine:fixture:load`

Le fichier `LoadUsersData.php` et `PutUsersInGroupsData.php` sont commentés.



**ATTENTION !!!**
* Avant toute chose faire gaffe à taper un `composer install` dans l'invite de commande afin d'installer le package 
symfony pour les fixtures
* Générer les fixtures supprimes les données déjà existantes. Pour ajouter, la commande est la même suivi de l'option `--append`