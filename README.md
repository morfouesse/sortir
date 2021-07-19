# sortir

Guide d'installation

1- dans le répertoire cible :
  1.1- git clone
  1.2- npm install
  1.3- composer install
  
2- dans l'IDE : 
  2.1- installation du plugin symfony support s'il ne l'est pas déjà
  2.2- ctrl-alt-s -> "symfony" -> Enable Plugin
                                  App Directory : src
                                  Web Directory : public
                                  Tout décocher sauf la dernière checkbox
  2.3- Créer le fichier .env.local
  2.4- mise en place de la BDD : doctrine:database:create
                                doctrine:schema:update (--force)
                                doctrine:fixtures:load
  2.5- npm run watch
