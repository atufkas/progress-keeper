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
 * 2. Progress Keeper adds some more types or aliases, not listed in the (technical) "commitizen - commit with conventions"
 * or Angular Commit Guidelines. Most types or equivalents con be found in the "keep-a-changelog" spec:
 *
 * Added:       for new features.                       : "feat"
 * Changed:     for changes in existing functionality.  : "update"
 * Deprecated:  for soon-to-be removed features.        : <none> (this is semantically wrong: Planning is not an actual change!)
 * Removed:     for now removed features.               : "removed"
 * Fixed:       for any bug fixes.                      : "fix"
 * Security:    in case of vulnerabilities.             : "security"
 *
 * A noe to "update". (keep-a-changelog: "Changed") While this may sound a bit generic and unspecific, it makes a lot
 * of sense especially but not only for changes affecting UI/UX: Updates should describe changes to the richness,
 * interface, behaviour or accessibility of EXISTING features. Those changes may also introduce a BREAKING CHANGE
 * in a technical (API, front end) manner.
 *
 * Class LogEntryType
 * @package atufkas\ProgressKeeper\LogEntry
 */
class LogEntryType
{
    /**
     * Documentation only changes
     */
    const PGTYPE_DOC = 'doc';
    /**
     * A new feature
     */
    const PGTYPE_FEAT = 'feat';
    /**
     * An enhanced feature or an updated behaviour of a feature
     */
    const PGTYPE_UPD = 'upd';
    /**
     * An removed feature
     */
    const PGTYPE_REM = 'rem';
    /**
     * A bug fix
     */
    const PGTYPE_FIX = 'fix';
    /**
     * A code change that improves performance
     */
    const PGTYPE_PERF = 'perf';
    /**
     * A code change that improves security o fixes a security issue
     */
    const PGTYPE_SECUR = 'secur';
    /**
     * A code change that neither fixes a bug nor adds a feature
     */
    const PGTYPE_REFAC = 'refac';
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

        self::PGTYPE_DOC => [
            'doc',
            'docs', // commitizen
            'docu',
            'documentation'
        ],
        self::PGTYPE_FEAT => [
            'feat',  // commitizen
            'feature',
            'add',
            'added',
            'new',
            'new feat',
            'new feature'
        ],
        self::PGTYPE_UPD => [
            'upd',
            'update',
            'upd',
            'change',
            'changed',
            'improvement',
            'enhancement'
        ],
        self::PGTYPE_REM => [
            'rem',
            'removed',
            'remove',
            'rm',
            'delete',
            'deleted',
            'del'
        ],
        self::PGTYPE_FIX => [
            'fix',  // commitizen
            'fixed',
            'bug',
            'bugfix',
            'bug fix'
        ],
        self::PGTYPE_PERF => [
            'perf', // commitizen
            'performance'
        ],
        self::PGTYPE_SECUR => [
            'secur',
            'security',
            'security issue',
            'security fix'
        ],
        self::PGTYPE_REFAC => [
            'refac',
            'refactor', // commitizen
            'refactored'
        ],
        self::PGTYPE_REVERT => [
            'revert',
            'reverted',
            'rev'
        ],
        self::PGTYPE_STYLE => [
            'style', // commitizen
            'cleanup',
            'cleanups',
            'formatting',
            'formatted'
        ],
        self::PGTYPE_TEST => [
            'test', // commitizen
            'tests',
            'unit test',
            'functional test',
            'integration test'
        ],
        self::PGTYPE_CHORE => [
            'chore', // commitizen
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

    /**
     * @param $type
     * @return mixed
     * @throws LogEntryException
     */
    public static function getCanonicalType($type)
    {
        if (array_key_exists($type, static::PGTYPE_ALIASES)) {
            return $type;
        }

        foreach (static::PGTYPE_ALIASES as $key => $aliases) {
            if (in_array($type, $aliases)) {
                return $key;
            }
        }

        throw new LogEntryException(sprintf('Type "%s" unknown and not found in alias list.', $type));
    }
}