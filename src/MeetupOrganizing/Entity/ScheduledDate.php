<?php
declare(strict_types=1);

namespace MeetupOrganizing\Entity;

use DateTimeImmutable;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

final class ScheduledDate
{
    const DATE_TIME_FORMAT = 'Y-m-d H:i';

    /**
     * @var string
     */
    private $dateTime;

    private function __construct(string $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public static function fromString(string $dateTime): ScheduledDate
    {
        try {
            $dateTimeImmutable = DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $dateTime);
            if ($dateTimeImmutable === false) {
                throw new RuntimeException('The provided date/time string did not match the expected format');
            }
        } catch (Throwable $throwable) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid date/time format. Provided: %s, expected format: %s',
                    $dateTime,
                    self::DATE_TIME_FORMAT
                ),
                0,
                $throwable
            );
        }

        return self::fromDateTime($dateTimeImmutable);
    }

    public static function fromDateTime(DateTimeImmutable $dateTime): ScheduledDate
    {
        return new self($dateTime->format(self::DATE_TIME_FORMAT));
    }

    public function asString(): string
    {
        return $this->dateTime;
    }

    public function isInTheFuture(DateTimeImmutable $now): bool
    {
        return $now < $this->toDateTimeImmutable();
    }

    public function toDateTimeImmutable(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $this->dateTime);
    }
}
