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

2. Configuration
----------------------

Configure bundle with your own entities in your **app/config.yml** file. Example:
```yaml
click_and_mortar_import:
  entities:
    customer_from_pim:
      model: Acme\DemoBundle\Entity\Customer
      repository: AcmeDemoBundle:Customer
      unique_key: id
      complete_mapping_method: myCustomSetterForRow
      mappings:
        id: "ID"
        name: "Name_For_Customer"
        firstname: "FirstName"
        gender: "Sex"
        age: "Age"
        ...
```

You can define multiple imports to a single entity by simply changing the name of the import procedure (eg. add a new part under **entities** with name **customer_from_ecommerce**)

**Options available:**

| Option                  | Mandatory | Example                         |                                                                                                                                          Comment |
|-------------------------|:---------:|---------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------:|
| model                   | Yes       | Acme\DemoBundle\Entity\Customer | Model name in your project                                                                                                                       |
| repository              | Yes       | AcmeDemoBundle:Customer         | Repository name for entity                                                                                                                       |
| unique_key              | No        | id                              | Allows entities update from a property                                                                                                           |
| complete_mapping_method | No        | myCustomImportMethod            | Extension point for passing data from the current line to a method of the entity (can be used to make changes on the data before assigning them) |

3. Usage
----------------------

Launch import of file with command:
```bash
php app/console candm:import --delete-after-import /path/of/your/file/customers.csv customer_from_pim
```