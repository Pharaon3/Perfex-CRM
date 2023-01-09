<?php

namespace app\services\imap;

use Ddeboer\Imap\MailboxInterface;
use Ddeboer\Imap\ImapResourceInterface;
use Ddeboer\Imap\Exception\ReopenMailboxException;
use Ddeboer\Imap\ImapResource as BaseImapResource;

class ImapResource implements ImapResourceInterface
{
    /**
     * @var resource
     */
    private $resource;

    private ?MailboxInterface $mailbox = null;

    private static ?string $lastMailboxUsedCache = null;

    /**
     * Constructor.
     *
     * @param resource $resource
     */
    public function __construct($resource, MailboxInterface $mailbox = null)
    {
        $this->resource = $resource;
        $this->mailbox  = $mailbox;
    }

    public function clearLastMailboxUsedCache(): void
    {
        self::$lastMailboxUsedCache = null;
    }

    /**
     * If connection is not currently in this mailbox, switch it to this mailbox.
     */
    private function initMailbox(): void
    {
        if (null === $this->mailbox || self::isMailboxOpen($this->mailbox, $this->resource)) {
            return;
        }

        \set_error_handler(static function (): bool {
            return true;
        });

        \imap_reopen($this->resource, $this->mailbox->getFullEncodedName());

        \restore_error_handler();

        if (self::isMailboxOpen($this->mailbox, $this->resource)) {
            return;
        }

        throw new ReopenMailboxException(\sprintf('Cannot reopen mailbox "%s"', $this->mailbox->getName()));
    }

    /**
     * Check whether the current mailbox is open.
     *
     * @param resource $resource
     */
    private static function isMailboxOpen(MailboxInterface $mailbox, $resource): bool
    {
        $currentMailboxName = $mailbox->getFullEncodedName();
        if ($currentMailboxName === self::$lastMailboxUsedCache) {
            return true;
        }

        self::$lastMailboxUsedCache = null;
        $check                      = \imap_check($resource);
        $return                     = false !== $check && $check->Mailbox === $currentMailboxName;

        if (true === $return) {
            self::$lastMailboxUsedCache = $currentMailboxName;
        }

        return $return;
    }

    public function getStream()
    {
        if (
            !$this->resource instanceof \IMAP\Connection
            && (false === \is_resource($this->resource) || 'imap' !== \get_resource_type($this->resource))
        ) {
            throw new InvalidResourceException('Supplied resource is not a valid imap resource');
        }

        $this->initMailbox();

        return $this->resource;
    }
}
