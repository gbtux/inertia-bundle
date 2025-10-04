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



## TODO

- AJouter InertiaJS à la conf NPM https://www.npmjs.com/package/@inertiajs/react
- 