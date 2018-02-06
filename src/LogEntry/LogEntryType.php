<?php

namespace atufkas\ProgressKeeper\LogEntry;

/**
 * Class specifying cpmmit message types following "conventional commits"
 * and parts of the Angular "Commit Message Guidelines"
 *
 * NOTES:
 *
 * 1. THe current Angular Commit Message Guidelines (as of Feb. 6th 2018) add two more types not listed here:
 *  - "build": Changes that affect the build system or external dependencies (example scopes: gulp, broccoli, npm)
 *  - "ci": Changes to the CI configuration files and scripts (example scopes: Travis, Circle, BrowserStack, SauceLabs)
 * These types are handled as aliases for "chore" in this implementation.
 *
 * 2. Progress Keeper adds one more type, not listed anywhere else: "update". This sounds quite generic and
 * unspecific but makes a lot of sense especially but not only for changes affecting UI/UX: Updates should describe
 * changes to the richness, interface, behaviour or accessibility of EXISTING features. Those changes may also
 * introduce a BREAKING CHANGE in a technical (API, front end) manner.
 *
 * Class LogEntryType
 * @package atufkas\ProgressKeeper\LogEntry
 */
class LogEntryType
{
    /**
     * Documentation only changes
     */
    const PGTYPE_DOCS = 'docs';
    /**
     * A new feature
     */
    const PGTYPE_FEAT = 'feat';
    /**
     * An enhanced feature or an updated behaviour of a feature
     */
    const PGTYPE_UPDATE = 'update';
    /**
     * A bug fix
     */
    const PGTYPE_FIX = 'fix';
    /**
     * A code change that improves performance
     */
    const PGTYPE_PERF = 'perf';
    /**
     * A code change that neither fixes a bug nor adds a feature
     */
    const PGTYPE_REFACTOR = 'refactor';
    /**
     * A change that reverts to a previous state
     */
    const PGTYPE_REVERT = 'revert';
    /**
     * Changes that do not affect the meaning of the code (white-space, formatting, missing semi-colons, etc)
     */
    const PGTYPE_STYLE = 'style';
    /**
     * Adding missing tests or correcting existing tests
     */
    const PGTYPE_TEST = 'test';
    /**
     * Changes to the build process or auxiliary tools and libraries such as documentation generation
     */
    const PGTYPE_CHORE = 'chore';

    /**
     * Aliases
     */
    const PGTYPE_ALIASES = [

        self::PGTYPE_DOCS => [
            'docs',
            'doc',
            'documentation'
        ],
        self::PGTYPE_FEAT => [
            'feat',
            'feature',
            'new',
            'new feat',
            'new feature'
        ],
        self::PGTYPE_UPDATE => [
            'update',
            'upd',
            'change',
            'improvement',
            'enhancement'
        ],
        self::PGTYPE_FIX => [
            'fix',
            'bug',
            'bugfix',
            'bug fix',
            'fixed'
        ],
        self::PGTYPE_PERF => [
            'perf',
            'performance'
        ],
        self::PGTYPE_REFACTOR => [
            'refactor',
            'refactored',
            'refac'
        ],
        self::PGTYPE_REVERT => [
            'revert',
            'reverted'
        ],
        self::PGTYPE_STYLE => [
            'style',
            'clseanup',
            'cleanups',
            'formatting',
            'formatted'
        ],
        self::PGTYPE_TEST => [
            'test',
            'tests',
            'unit test',
            'functional test',
            'integration test'
        ],
        self::PGTYPE_CHORE => [
            'chore',
            'ci',
            'build',
            'lib',
            'libs',
            'dep',
            'deps',
            'code',
            'internal',
            'code/internal'
        ],
    ];
}