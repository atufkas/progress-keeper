<?php

namespace atufkas\ProgressKeeper\LogEntry;

/**
 * Class LogEntry
 * @package atufkas\ProgressKeeper\LogEntry
 */
class LogEntry
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $scope;

    /**
     * The log entry audience, e.g.: *|dev|user
     * @var $audience
     */
    protected $audience;

    /**
     * @var \DateTimeImmutable
     */
    protected $date;

    /**
     * @var string
     */
    protected $desc;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var string
     */
    protected $footer;

    /**
     * LogEntry constructor.
     * @param string $ccMessage
     * @param string $audience
     * @param \DateTimeImmutable|null $date
     * @throws LogEntryException
     */
    public function __construct($ccMessage = 'misc: progress', $audience = '*', \DateTimeImmutable $date = null)
    {
        $ccMessageElements = static::parseElementsFromCcMessage($ccMessage);

        $this->type = $ccMessageElements['type'];
        $this->scope = $ccMessageElements['scope'];
        $this->desc = $ccMessageElements['desc'];

        $this->audience = $audience;

        if (!$date) {
            $date = new \DateTimeImmutable('now');
        }

        $this->date = $date;
    }

    /**
     * @param array $entryArr
     * @throws LogEntryException
     *
     * TODO: Extract as factory method
     */
    public function createFromArray(array $entryArr)
    {
        $mandatoryKeys = ['date', 'type', 'desc'];

        foreach ($mandatoryKeys as $mandatoryKey) {
            if (!isset($entryArr[$mandatoryKey])) {
                throw new LogEntryException(sprintf('Mandatory key "%s" not found in log entry array.', $mandatoryKey));
            }
        }

        foreach ($entryArr as $key => $value) {

            switch ($key) {
                case 'type':
                    $this->setTypeByCcType($value);
                    break;

                case 'scope':
                    // Explicitly passed scopes override scopes parsed from type
                    $this->setScope($value);
                    break;

                case 'date':
                    $this->setDate(\DateTimeImmutable::createFromFormat('Y-m-d', $value));
                    break;

                case 'desc':
                    $this->setDesc($value);
                    break;

                case 'audi':
                case 'audience':
                    $this->setAudience($value);
                    break;

                case 'body':
                    $this->setBody($value);
                    break;

                case 'footer':
                    $this->setFooter($value);
                    break;
            }
        }
    }

    /**
     * Parse elements (type, scope, description) from "conventional commit" message.
     * Message string missing type and optional scope are handled gracefully so that
     * an array with whole message string as value for key "desc" is returned.
     * Messages of the following format will be matched:
     *
     * - type: description
     * - [type]: description
     * - type(scope): description
     * - type (scope): description
     * - [type(scope)]: description
     * - [type (scope)]: description
     *
     * @param $message
     * @param bool $canonical
     * @return array
     * @throws LogEntryException
     */
    public static function parseElementsFromCcMessage($message, $canonical = true)
    {
        $res = [
            'type' => 'misc',
            'scope' => null,
            'desc' => $message
        ];

        $ccTypePatterns = implode('|', LogEntryType::getUniqueTypeAliases());

        $regex = '/^(\[?\b(' . $ccTypePatterns . ')\b[\s]?(\((\b.*\b)\))?\]?\:[\s]?)(.*)$/si';

        if (preg_match($regex, $message, $matches)) {
            $res = [
                'type' => $canonical ? LogEntryType::getCanonicalIdentifier($matches[2]) : $matches[2],
                'scope' => $matches[4],
                'desc' => $matches[5]
            ];
        }

        return $res;
    }

    /**
     * Parse elements (type, scope) from "conventional commit" type.
     * Type string missing scope are handled gracefully so that
     * an array with whole type string as value for key "type" is returned.
     * Types of the following format will be matched:
     *
     * - type
     * - type(scope)
     *
     * @param $type
     * @param bool $canonical
     * @return array
     * @throws LogEntryException
     */
    public static function parseElementsFromCcType($type, $canonical = true)
    {
        $ccTypePatterns = implode('|', LogEntryType::getUniqueTypeAliases());

        $regex = '/^(\b' . $ccTypePatterns . '\b)[\s]?\((\b.*\b)\)$/si';

        if (preg_match($regex, strtolower($type), $matches)) {
            $res = [
                'type' => $canonical ? LogEntryType::getCanonicalIdentifier($matches[1]) : $matches[1],
                'scope' => $matches[2]
            ];
        } else {
            $res = [
                'type' => LogEntryType::getCanonicalIdentifier($type),
                'scope' => null
            ];
        }

        return $res;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type and scope by parsing "conventional commit" style type
     * @param $ccType
     * @throws LogEntryException
     */
    public function setTypeByCcType($ccType)
    {
        $ccTypeElements = static::parseElementsFromCcType($ccType);
        $this->setType($ccTypeElements['type']);
        $this->setScope($ccTypeElements['scope']);
    }

    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return mixed
     */
    public function getAudience()
    {
        return $this->audience;
    }

    /**
     * @param mixed $audience
     */
    public function setAudience($audience)
    {
        $this->audience = $audience;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTimeImmutable $date
     */
    public function setDate(\DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param mixed $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @param mixed $footer
     */
    public function setFooter($footer)
    {
        $this->footer = $footer;
    }
}