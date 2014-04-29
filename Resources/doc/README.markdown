# CCETC/ImportBundle

The CCETCImportBundle is a Symfony bundle for importing xls spreadsheets to your Symfony DB.

It is in beta, and is only currently written to be used with ``SonataAdmin`` and ``Doctrine``.  It is also only meant to be used by developers at this point. 

Development is tracked on the [trello board](https://trello.com/board/importbundle/512d4327c6c0852e5900046a).

## Installation
Add to your composer.json:

    "require": {
        "ccetc/import-bundle": "dev-master"
    }

Run ``php composer.phar install``

Add to AppKernel.php:

    new CCETC\ImportBundle\CCETCImportBundle(),


### Security
The tool is accessed at ``admin/import``, so make sure this route is secured in ``security.yml``.

## Handlers
You'll need to write a ``handler`` to essentially map your database to a xls with an expected set of fields.  Your handler should exist as a service in your application, and should extend a handler in ``Import/Handler``.

## Use
Navigate to ``admin/import``, and specify your handler service and file path.
