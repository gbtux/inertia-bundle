# InertiaBundle

symfony new projetTest2
Modifier le composer.json tel que:
```
"repositories": [
    {
        "type": "path",
        "url": "../inertia-bundle"
    }
]
```

Modifier dans ce même fichier :
```
{
  "extra": {
    "symfony": {
      "allow-contrib": true,
      "endpoint": [
        "flex://defaults",
        "../inertia-bundle-recipe"
      ]
    }
  }
}
```
If needed : ```nvm use 22```

dans config/routes/fos_js_routing.yaml, vérifier la configuration telle que:
```
fos_js_routing:
    resource: "@FOSJsRoutingBundle/Resources/config/routing/routing.php"
```
En Symfony 8, le fichier doit être le php, pas le xml

## TODO

- AJouter InertiaJS à la conf NPM https://www.npmjs.com/package/@inertiajs/react
- 