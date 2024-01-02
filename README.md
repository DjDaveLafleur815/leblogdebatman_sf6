# projet Le Blog de Batman

## Installation

### Cloner le projet

``` 
git clone https://github.com/DjDaveLafleur815/leblogdebatman_sf6.git
```

### Déplacer le terminal dans le dossier cloné

```
cd leblogdebatman
```

### Taper les commandes suivantes

```
composer install
symfony console doctrine:database:create
symfony console make:migration
symfony console doctrine:migration:migrate
```
