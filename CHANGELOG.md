# Progress-Keeper
Track, transform and present changelog entries.

## 0.3.3

Date: 03.05.2018

Small service release with few updates on internal dependencies.

- chore: Updated to latest composer version (included as phar file).
- chore: Updated dependency on league/commonmark (0.17.0 -> 0.17.5).

## 0.3.2

Date: 26.03.2018

Small bugfix release providing a fix for the HTML presenter.

- fix: HTML presenter won't convert special characters into their corresponding html entities.

## 0.3.1

Date: 26.02.2018

Small service release providing some improvements in detail.

- doc: Improved introduction, corrected some details about current API, added hints for contributors.
- upd: BREAKING CHANGE: HTML presenter now renders the log entry scope value (if present) as prefix of the description.
- upd: HTML presenter now adds a css class 'pk-logentry-type-[type]' class to log entry inline element.
- chore(ci): Added additional Travis CI test runner for PHP 7.2.

## 0.3.0

Date: 26.02.2018

Simple markdown presenter, new progress-keeper command, Travis CI integration and more.

- doc: Added Travis CI badge.
- doc: Added syntax highlighting for JSON changelog example.
- doc: Updated README by adding a paragraph about the internal JSON format plus some basic model and API concepts.
- feat: Added new reader type: Markdown. Parses a markdown changelog from AST which is structured like the one the markdown presenter generates.
- feat: Extended LogEntryType by introducing field 'scope' and adding methods parsing type, scope and description from conventional commit messages and types.
- feat: Added simple command which calls factory method 'getConvertedChangelog' with CLI arguments.
- feat: Added new presenter: Markdown. Allows for generating simple markdown from given changelog.
- upd: BREAKING CHANGE: Markdown presenter now renders types in the form 'type:' instead of '[type]'.
- upd: BREAKING CHANGE: Changed output for HTML presenter: New tag structure and prettified formatting.
- refac: BREAKING CHANGE: Renamed methods for parsing changelog, releases and log entries from from array to readFromArray().
- refac: BREAKING CHANGE: Changed signature of LogEntryType constructor now parsing first argument as 'conventional commit message'.
- refac: Extracted AbstractReader providing basic member and access methods.
- refac: Extracted method getCanonicalType() and moved it to class LogEntryType.
- test: Added test class for testing presenters + extracted sample changelog generation to JsonSampleTextCase super class.
- chore: Locked composer deps to PHP 5.6 compatible versions.
- chore(ci): Added integration with Travis CI.
- chore: Added composer 'pre-commit' script which builds markdown and HTML versions of the own PK changelog.
- chore: Renamed 'release-info.json' files to 'pk-changelog.json'.

## 0.2.0

Date: 14.02.2018

New log entry type list + filter and sort methods + a lot of housekeeping.

- feat: Added method to fetch latest release (i.e. top most) of a changelog.
- feat: Added method to fetch version string of latest release (i.e. top most) of a changelog.
- feat: Added methods to order (group) log entries by type, using default or a custom type order.
- feat: Added methods to filter log entries to specific audience(s).
- upd: BREAKING CHANGE: Minor corrections of class names for releases -> release -> logentries -> logentry.
- upd: Added (most) log entry types found in "keep-a-changelog" and various log entry (commit) type aliases.
- upd: BREAKING CHANGE: Field identifier (key) 'adnc' is replaced by 'audi' as an alias for 'audience'.
- refac: BREAKING CHANGE: Removed class ProgressKeeper and refactored methods of ProgressKeeperInterface.
- test: Renaming test classes after unintentionally adding fragment 'sample' to all test cases.
- chore: Consistently renamed changeLog (camelCase) to changelog (lowercase).

## 0.1.0

Date: 08.02.2018

Initial release: Provides a basic factory class methods allowing a json -> html transformation.

- doc: Clarified main goals of this project + some cosmetic changes.
- doc: Committing this project to the follow the principles of conventional-changelog and keep-a-changelog.
- feat: Added methods for adding releases to changelogs. Object model hierarchy is now: changelog -> releases -> log entries
- feat: Added methods for adding log entries to releases.
- feat: Added classes representing log entries and releases.
- feat: Very first sketch using a Reader/Presenter interface concept and a ProgressKeeperFactory.
- fix: Only mandatory log entry fields would be correctly set by parse-from-array method.
- chore: Release 0.1.0

