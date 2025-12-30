# InertiaBundle for Symfony 7/8

[![Symfony 7.0+](https://img.shields.io/badge/Symfony-7.x%20%2F%208.x-blueviolet.svg?style=flat-square)](https://symfony.com)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-blue.svg?style=flat-square)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)](LICENSE)

Ce bundle intègre de manière fluide **Inertia.js** dans vos applications Symfony. 
Créez des applications "Single Page" (SPA) modernes en utilisant les contrôleurs Symfony et vos composants React préférés, sans la complexité d'une API REST ou GraphQL.

---

## 🚀 Installation

Suivez ces étapes pour configurer votre projet Symfony avec InertiaJS.

### 1. Prérequis : Un projet propre
Il est recommandé de partir sur une base sans les outils "Hotwire" par défaut de Symfony pour éviter les conflits. Créez votre projet sans Stimulus ni Turbo :

```bash
symfony new my_project
#ou
composer create-project symfony/skeleton my_project
```

Assurez-vous également que votre environnement dispose d'une version récente de Node.js :

* **Node.js > 22** est requis.

### 2. Installation du Bundle

Installez le package via Composer :

```bash
composer require gbtux/inertia-bundle

```

> [!IMPORTANT]
> **En cas d'erreur lors de l'installation :**
> Si vous rencontrez un problème lié au `FOSJsRoutingBundle`, vérifiez que votre fichier `config/routes/fos_js_routing.yaml` contient bien la configuration suivante :
> ```yaml
> fos_js_routing:
>     resource: "@FOSJsRoutingBundle/Resources/config/routing/routing.php"
> 
> ```
>
>
> Une fois vérifié, relancez la commande `composer require gbtux/inertia-bundle`.

### 3. Initialisation automatique

Le bundle inclut une commande pour configurer automatiquement l'arborescence, les fichiers Vite, les templates et les routes :

```bash
php bin/console inertia:install
```

### 4. Lancement du projet

Ouvrez un terminal pour démarrer vos serveurs :

**Serveur Symfony**

```bash
symfony serve -d
```

**Compilation des Assets (Vite)**

```bash
npm run dev

```

---

## 💡 Utilisation

### Dans votre Contrôleur

Héritez de `Gbtux\InertiaBundle\Controller\AbstractController;` pour accéder à la méthode `renderInertia`.

```php
// src/Controller/HomeController.php
namespace App\Controller;

use Gbtux\InertiaBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index()
    {
        return $this->renderInertia('Home', [
            'user' => $this->getUser()?->getUserIdentifier(),
            'version' => '1.0'
        ]);
    }
}

```

### Côté Frontend (React)

Inertia transmet automatiquement les données de Symfony comme des **props**.

```jsx
import React from "react";
import { Head } from "@inertiajs/react";

export default function AboutPage() {
    return (
        <>
            <Head title="About Us" />
            <h1>About Us</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
        </>
    );
}
```

---

## 🔗 Shared Data (Données Partagées)

Vous pouvez partager des données automatiquement avec tous vos composants (comme l'utilisateur connecté ou les messages flash) via un EventListener ou en surchargeant la configuration. Par défaut, le bundle gère les messages flash de Symfony :

```js
// Dans n'importe quel composant, accédez aux flash messages
const flash = usePage().props.flash;

```

---

## 🛠️ Fonctionnalités

* **Zero-config** : La commande `inertia:install` s'occupe de tout.
* **Routing** : Intégration native avec `FOSJsRoutingBundle` pour utiliser vos routes Symfony en JS.
* **Vite** : Configuration moderne optimisée pour les performances.

---

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une *Issue* ou à soumettre une *Pull Request*.

---

## 📄 Licence

Ce bundle est sous licence **MIT**.

---

Fait avec ❤️ par [Gbtux](https://github.com/gbtux)