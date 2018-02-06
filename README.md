# progress-keeper

[![Conventional Commits](https://img.shields.io/badge/Conventional%20Commits-1.0.0-yellow.svg)](https://conventionalcommits.org)

Track, transform and present change log entries.

++ **Attention**: Project is currently in the state of an early stage concept under active development. 
API may change frequently. Feel free to ask, comment and contribute! +++


## What's this? (the short story)

This project aims to address the problem of generating **user as well as developer friendly change log 
catalogues supporting various formats, generated from various sources**. One major goal is to **aggregate change
log information based on relevance for specific audience**. The implementation and specification of formats follows
some best practices, recommendations and proposals like the [Conventional Commits Specification](https://conventionalcommits.org/).


## State

There's currently a very rough sketch using a Reader/Presenter interface concept and a ProgressKeeperFactory 
for dynamically instantiating a Reader-Presenter output vector.


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
