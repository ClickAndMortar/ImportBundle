# ImportBundle - C&M

> Import Bundle can be used to populate entities from flat files (.csv, .xml, etc.)

## Installation

### Download the Bundle

```console
$ composer require clickandmortar/import-bundle
```

### Enable the Bundle

Enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new ClickAndMortar\ImportBundle\ClickAndMortarImportBundle(),
        ];

        // ...
    }

    // ...
}
```

## Configuration

Configure bundle with your own entities in your **app/config.yml** file. Example:
```yaml
click_and_mortar_import:
  entities:
    customer_from_pim:
      model: Acme\DemoBundle\Entity\Customer
      repository: AcmeDemoBundle:Customer
      unique_key: id
      mappings:
        id: "ID"
        name: "Name_For_Customer"
        firstname: "FirstName"
        gender: "Sex"
        age: "Age"
        ...
```

You can define multiple imports for a single entity by simply changing the name of the import procedure (eg. add a new part under **entities** with name **customer_from_ecommerce**)

**Options available:**

| Option                  | Mandatory | Example                                 |                                                                                                                                          Comment |
|-------------------------|:---------:|-----------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------:|
| model                   | Yes       | Acme\DemoBundle\Entity\Customer         | Model name in your project                                                                                                                       |
| repository              | Yes       | AcmeDemoBundle:Customer                 | Repository name for entity                                                                                                                       |
| unique_key              | No        | id                                      | Allows entities update from a property                                                                                                           |
| only_update             | No        | false                                   | If true, only update existing entities by using unique_key                                                                                       |
| import_helper_service   | No        | acme.demo.import_helper.my_import_helper| Extension point to complete classic mapping data on entity. Service must implements "ImportHelperInterface" interface                            |

## Usage

Launch import of file with command:
```bash
php app/console candm:import /path/of/your/file/customers.csv customer_from_pim
```

**Options available:**

| Option                | Comment                  |
|-----------------------|--------------------------|
| --delete-after-import | Delete file after import |

## Extension

You can create your own reader to read other file types.

Create your class (in **YourOrganizationName/YourBundle/Reader/Readers**) and extends **AbstractReader**:
```php
<?php

namespace YourOrganizationName\YourBundle\Reader\Readers;

use ClickAndMortar\ImportBundle\Reader\AbstractReader;

/**
 * Class MyCustomXmlReader
 *
 * @package YourOrganizationName\YourBundle\Reader\Readers
 */
class MyCustomXmlReader extends AbstractReader
{
    /**
     * Read my custom XML file and return data array
     *
     * @param string $path
     *
     * @return array
     */
    public function read($path)
    {
        $data = array();

        // ...

        return $data;
    }

    /**
     * Support only xml type
     *
     * @param string $type
     *
     * @return bool
     */
    public function support($type)
    {
        return $type == 'xml';
    }
}
```

Declare class as service (in **YourOrganizationName/YourBundle/Resource/config/services.yml**) and add tag **clickandmortar.import.reader**:
```yaml
parameters:
  yourorganizationname.yourbundle.reader.my_custom_reader.class: YourOrganizationName\YourBundle\Reader\Readers\MyCustomXmlReader

services:
  yourorganizationname.yourbundle.reader.my_custom_reader:
    class: %yourorganizationname.yourbundle.reader.my_custom_reader.class%
    tags:
      - { name: clickandmortar.import.reader }
```

And that's all!

