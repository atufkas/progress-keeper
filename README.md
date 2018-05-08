# progress-keeper

[![Build Status](https://travis-ci.org/atufkas/progress-keeper.svg?branch=master)](https://travis-ci.org/atufkas/progress-keeper)
[![Conventional Commits](https://img.shields.io/badge/Conventional%20Commits-1.0.0-yellow.svg)](https://conventionalcommits.org)

Track, transform and present changelog entries.

+++ **Note**: This project is in the state of an early stage
concept and under active development. API may change frequently. Feel free
to ask, comment and contribute, I'd be happy to receive help and support!  +++


## Introduction

This project aims to address the problem of generating user as well as
developer friendly changelog catalogues supporting various formats,
generated from various sources. One major goal is to **aggregate and
present structured change log information based on relevance for a
specific audience**. The implementation and specification of formats
follows some best practices, recommendations and proposals like the
[Conventional Commits Specification](https://conventionalcommits.org/).


## Installation

This is a PHP 5.6+ / 7.0+ composer based project. A composer.phar archive bundle is included.

Clone project

    $ git clone https://github.com/atufkas/progress-keeper
    $ cd progress-keeper
    
Install dependencies
    
    $ php ./composer.phar install


## Running tests
    
    $ php ./composer.phar test      # Run tests by either calling composer defined script
    $ ./vendor/bin/phpunit          # ...or just calling phpunit directly.


## Examples 

### CLI

progress-keeper comes with a very simple CLI script that allows for converting a supported changelog source 
to a supported target format. Call it without arguments to receive a short help text:

    $ ./bin/progress-keeper
    
For testing a conversion of the project changelog (stored as JSON), you may run the following commands 
(results are written to stdout):

    $ ./bin/progress-keeper ./pk-changelog.json json markdown
    $ ./bin/progress-keeper ./pk-changelog.json json html


### API

There are a few factory methods for giving quick access to the most common use cases. For parsing a changelog 
source into the internal object representation just do something like this:
        
        use atufkas\ProgressKeeper\ProgressKeeperFactory;

        $releaseLogFile = __DIR__ . '/my-changelog.json';
        $changelog = ProgressKeeperFactory::getChangelog($releaseLogFile, 'json', ['*'], true);


Have a look on that the 2nd parameter which is passed as an array containing just single element, a string with an 
asterisk ('*'): With this array you define to which audiences the selected log entries should be filtered down, 
where an asterisk has the meaning of "all audiences". If you just want to parse entries targeting the audience "user", 
the line should be modified to:

        $changelog = ProgressKeeperFactory::getChangelog($releaseLogFile, 'json', ['user'], true);

Yehaa, now play around and debug or dump output to see what the internal object structure looks like and how release and 
log entry data can be accessed:

        $latestRelease = $changelog->getLatestRelease();                    // Get latest release object
        $currentVersion = $latestRelease->getVersionString();               // Get version string of a release
        $logEntries = $currentVersion->getLogEntries();                     // Get all log entries of a release
        $bugfixLogEntries = $currentVersion->getLogEntriesByType('fix');    // Get all log entries of type "bugfix"

You may now do whatever you want with the changelog data or use one of the writer (presenter) plugins to 
programmatically generate a changelog in a specific format, e.g. HTML:

        use atufkas\ProgressKeeper\Presenter;
        
        $htmlPresenter = new HtmlPresenter();
        $htmlPresenter->setChangelog($changelog);
        $changelogHtml = $htmlPresenter->getOutput();
        
Same thing could be achieved by another factory method in one step:

        $changelogHtml = ProgressKeeperFactory::getConvertedChangelog($releaseLogFile, 'json', 'html', ['user'], true);

For some more details on the concepts and available reader/presenter plugins see next section.



## Concepts + API

The project is under active development. New features, concepts and
breaking changes are introduced frequently. Currently this library provides:

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
              "type": "fix(ui)",
              "desc": "Validation failed when whitespace was transmitted via form value.",
              "audience": "*"
            },
            {
              "date": "2018-01-17",
              "type": "chore",
              "scope": "ci",
              "desc": "Added support for Travis CI.",
              "audience": "dev"
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
    - Markdown: `atufkas\ProgressKeeper\Reader\MarkdownReader`

- a **target format presenter interface** with implementations for:
     - HTML: `atufkas\ProgressKeeper\Presenter\HtmlPresenter`
     - Markdown: `atufkas\ProgressKeeper\Presenter\MarkdownPresenter`

- **simple factory methods** allowing for generating a changelog from a given source file and source format
    - as internal Changelog PHP object: `atufkas\ProgressKeeperKeeperFactory::getChangelog`
    - in target presentation format (by passing target format as additional argument): `atufkas\ProgressKeeperKeeperFactory::getConvertedChangelog`

- a **very basic CLI command** `./bin/progress-keeper` passing command line arguments to
    - `atufkas\ProgressKeeperKeeperFactory::getConvertedChangelog`



## Contributing

No big deal, go ahead! Just follow current (PHP) coding standards and of course
write tests. Feel free to improve or critzize existing code or ideas, ask if
unsure about something or just generate an issue or pull request.
I'll to my best to review them as soon as possible.

When adding a feature or making a change, use progress-keeper to generate
own changelogs by adding a log entry to `./pk-changelog.json` and generate
new Markdows and HTML versions of the whole changelog by running:

    $ php ./composer.phar pre-commit

    
## Related to + heavily inspired by

* [keep a changelog](https://github.com/olivierlacan/keep-a-changelog) - If you build software, keep a changelog.
* [Conventional Commits Specification](https://conventionalcommits.org/)
* [Angular: "Commit Message Guidelines"](https://github.com/angular/angular/blob/master/CONTRIBUTING.md#commit)
* [conventional-changelog](https://github.com/conventional-changelog/conventional-changelog) - Generate a changelog from git metadata
* [commitizen](https://github.com/commitizen/cz-cli) - Simple commit conventions for internet citizens
* [commitlint](https://github.com/marionebl/commitlint) - Lint commit messages
