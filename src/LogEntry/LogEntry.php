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
     * @param string $type
     * @param string $audience
     * @param \DateTimeImmutable|null $date
     * @param null $desc
     * @throws LogEntryException
     */
    public function __construct($type = 'feat', $audience = '*', \DateTimeImmutable $date = null, $desc = null)
    {
        $this->type = LogEntryType::getCanonicalIdentifier($type);
        $this->audience = $audience;

        if (!$date) {
            $date = new \DateTimeImmutable('now');
        }

        $this->date = $date;
        $this->desc = $desc;
    }

    /**
     * @param array $entryArr
     * @throws LogEntryException
     */
    public function parseFromArray(array $entryArr)
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
                    $this->setType(LogEntryType::getCanonicalIdentifier(strtolower($value)));
                    break;

                case 'date':
                    $this->setDate(\DateTimeImmutable::createFromFormat('Y-m-d', $value));
                    break;

                case 'desc':
                case 'message':
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