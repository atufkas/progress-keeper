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
     * The log entry audience, e.g.: all|dev|user
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
     * @param $type
     * @param $audience
     * @param \DateTimeImmutable $date
     * @param $desc
     * @throws LogEntryException
     */
    public function __construct($type = 'feat', $audience = 'all', \DateTimeImmutable $date = null, $desc = null)
    {
        $this->type = $this->getCanonicalType($type);;
        $this->audience = $audience;

        if (! $date) {
            $date = new \DateTimeImmutable('now');
        }

        $this->date = $date;
        $this->desc = $desc;
    }

    /**
     * @param $entryArr
     * @throws LogEntryException
     */
    public function parseFromArray($entryArr)
    {
        $mandatoryKeys = ['date', 'type', 'desc'];

        foreach ($mandatoryKeys as $mandatoryKey) {
            if (!isset($entryArr[$mandatoryKey])) {
                throw new LogEntryException(sprintf('Mandatory key "%s" not found in log entry array.', $mandatoryKey));
            }

            $value = $entryArr[$mandatoryKey];

            switch($mandatoryKey) {
                case 'type':
                    $this->setType($this->getCanonicalType($value));
                    break;

                case 'date':
                    $this->setDate(\DateTimeImmutable::createFromFormat('Y-m-d', $value));
                    break;

                case 'desc':
                case 'message':
                    $this->setDesc($value);
                    break;

                case 'adnc':
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
     * @param $type
     * @return mixed
     * @throws LogEntryException
     */
    public function getCanonicalType($type)
    {
        if (array_key_exists($type, LogEntryType::PGTYPE_ALIASES)) {
            return $type;
        }

        foreach(LogEntryType::PGTYPE_ALIASES as $key => $aliases) {
            if (in_array($type, $aliases)) {
                return $key;
            }
        }

        throw new LogEntryException(sprintf('Type "%s" unknown and not found in alias list.', $type));
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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