# progress-keeper

Track, transform and present change log entries.

++ **Attention**: Project is currently in the state of an early stage concept under active development. 
API may change frequently. Feel free to ask, comment and contribute! +++


## What's this? (the short story)

This project aims to address the problem of generating **user as well as developer friendly change log 
catalogues supporting various formats, generated from various sources**. One goal is to aggregate based
on relevance for specific audience. It follows some best practices, de facto standards and proposals.


## Dev state

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
    