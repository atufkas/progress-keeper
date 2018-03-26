# PK Sample-App
The app with forms that make you happy!

## 1.0

Date: 30.01.2018

Now our app is ready for production.

- feat(ui): Added a batch mode for editing multiple records at once.
- fix(ui): Validation failed when whitespace was transmitted via form value.

## 0.2.0

Date: 24.01.2018

This version introduces this release log, updates internal dependencies and adds support for docker.

- feat: Changes will now be tracked and displayed with ProgressKeeper using JSON files and HTML-Bootstrap rendering.
- feat: Added server side validation for form fields, including mail address formats like "Sam Foo <samfoo@example.com>".
- feat: Modernized server side runtime environment, improving overall stability.
- upd(ui): Form field 'gender' is now rendered as radio button instead of select menu.
- chore: Set platform dependency to PHP >= 5.6.0.
- chore: Integrated docker support + setup.

## 0.1.1

Date: 22.01.2018

This release improves some UI styles and fixes a minor bug in the main form.

- upd: Form style is now more compact so that whole form is visible even on small screens.
- fix: Form cannot be submitted if name field is left empty but creates no notification.

## 0.1.0

Date: 22.01.2018

Initial release.

- feat: Added basic CRUD functions.
- feat: Added basic tooling and created skeleton.

