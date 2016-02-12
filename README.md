ImportBundle - Click And Mortar
=============================

Import Bundle can be used to populate entities from flat files (.csv, .xml, etc.)

1. Installation
----------------------

Add package and repository in your **composer.json** file:
```javascript
    "require": {
        ...
        "clickandmortar/import-bundle": "dev-master"
        ...
    },
    "repositories": [
            {
                "type": "vcs",
                "url": "git@bitbucket.org:clickandmortar/importbundle.git"
            }
        ],
```

Launch composer update to add bundle to your project:
```bash
composer update
```

Add bundle in your **app/AppKernel.php** file:
```php
    $bundles = array(
                ...
                new ClickAndMortar\ImportBundle\ClickAndMortarImportBundle(),
            );
```

And configure bundle with your own entities in your **app/config.yml** file. Example:
```yaml
    click_and_mortar_import:
      entities:
        frame:
          model: SextantVisual\ApiBundle\Entity\Frame
          repository: SextantVisualApiBundle:Frame
          unique_key: gtin
          mappings:
            gtin: "GTIN"
            type: "Type_Mont"
            age: "Age_Mont"
            gender: "Sexe_Mont"
            material: "Nature_Mont"
            ...
```