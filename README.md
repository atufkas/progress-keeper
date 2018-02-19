# progress-keeper

[![Build Status](https://travis-ci.org/atufkas/progress-keeper.svg?branch=master)](https://travis-ci.org/atufkas/progress-keeper)
[![Conventional Commits](https://img.shields.io/badge/Conventional%20Commits-1.0.0-yellow.svg)](https://conventionalcommits.org)

Track, transform and present changelog entries.

+++ **Importanat Note**: Project is currently in the state of an early stage
concept under active development.  API may change frequently. Feel free
to ask, comment and contribute! +++


## What's this? (the short story)

This project aims to address the problem of generating **user as well as
developer friendly changelog catalogues supporting various formats,
generated from various sources**. One major goal is to **aggregate change
log information based on relevance for specific audience**. The
implementation and specification of formats follows
some best practices, recommendations and proposals like the
[Conventional Commits Specification](https://conventionalcommits.org/).


## Concepts + API

The project is under active development. New features and conecpts are
introduced frequently. Currently this library provides:

- a definition for an "intermediate JSON changelog format" looking like this:
    ``` json
    {
      "name": "PK Sample-App",
      "desc": "The app with forms that make you happy!",
      "releases": [
        {
          "version": "1.0",
          "date": "2018-01-30 12:12",
          "remarks": "Now our app is ready for production.",
          "changelog": [
            {
              "date": "2018-01-26",
              "type": "feat",
              "desc": "Added a batch mode for editing multiple records at once.",
              "audience": "*"
            },
            {
              "date": "2018-01-17",
              "type": "fix",
              "desc": "Validation failed when whitespace was transmitted via form value.",
              "audience": "*"
            }
          ]
        },
        {
          "version": "0.2.0",
          "date": "2018-01-24 17:30",
          "remarks": "This version introduces this release log, updates internal dependencies and adds support for docker.",
          "changelog": [
            {
              "date": "2018-01-23",
              "type": "code",
              "desc": "Set platform dependency to PHP >= 5.6.0.",
              "audience": "dev"
            }
          ]
        }
      ]
    }
    ```


- a **changelog model** that reflects a structure of the form: Changelog
-> Releases -> Log Entries
    - `atufkas\ProgressKeeper\Changelog`
    - `atufkas\ProgressKeeper\Release\Release`
    - `atufkas\ProgressKeeper\LogEntry\LogEntry`

- a **source format reader interface** with implementations for:
    - JSON: `atufkas\ProgressKeeper\Reader\JsonReader`

- a **target format presenter interface** with implementations for:
     - HTML: `atufkas\ProgressKeeper\Presenter\HtmlPresenter`
     - Markdown: `atufkas\ProgressKeeper\Presenter\MarkdownPresenter`

- **simple factory methods** allowing for generating a changelog from a given source file and source format
    - Intermediate JSON format: `atufkas\ProgressKeeperKeeperFactory::getChangelog`
    - Target presentation format: `atufkas\ProgressKeeperKeeperFactory::getConvertedChangelog`

- a **very basic CLI command** passing command line arguments to
    - `atufkas\ProgressKeeperKeeperFactory::getConvertedChangelog`


## Installation for testing + developing

This is a PHP 5.6+ composer based project. A composer.phar archive bundle is included.

Clone project

    $ git clone https://github.com/atufkas/progress-keeper
    $ cd progress-keeper
    
Install dev dependencies
    
    $ php ./composer.phar install --dev
        
Run tests
    
    $ php ./composer.phar run-script test
    
    
## Related to + heavily inspired by

* [keep a changelog](https://github.com/olivierlacan/keep-a-changelog) - If you build software, keep a changelog.
* [Conventional Commits Specification](https://conventionalcommits.org/)
* [Angular: "Commit Message Guidelines"](https://github.com/angular/angular/blob/master/CONTRIBUTING.md#commit)
* [conventional-changelog](https://github.com/conventional-changelog/conventional-changelog) - Generate a changelog from git metadata
* [commitizen](https://github.com/commitizen/cz-cli) - Simple commit conventions for internet citizens
* [commitlint](https://github.com/marionebl/commitlint) - Lint commit messages
