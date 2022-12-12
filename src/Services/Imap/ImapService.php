<?php

namespace Eshop\Services\Imap;

use Carbon\Carbon;
use Eshop\Services\Imap\Exceptions\ImapException;
use Throwable;
use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\Client as ImapClient;
use Webklex\PHPIMAP\Folder;
use Webklex\PHPIMAP\Message;
use Webklex\PHPIMAP\Query\WhereQuery;
use Webklex\PHPIMAP\Support\MessageCollection;

class ImapService
{
    private string $account;
    private string $folderName;

    private ImapClient $client;

    public function __construct($account = 'gmail', $folderName = 'INBOX')
    {
        $this->account = $account;
        $this->folderName = $folderName;
    }

    /**
     * @return WhereQuery
     * @throws ImapException
     */
    public function query(): WhereQuery
    {
        try {
            return $this->folder()->query()->whereAll();
        } catch (Throwable $e) {
            throw new ImapException($e->getMessage());
        }
    }

    /**
     * @return WhereQuery
     * @throws ImapException
     */
    public function withoutBody(): WhereQuery
    {
        return $this->query()->setFetchFlags(false)->setFetchBody(false);
    }

    /**
     * @throws ImapException
     */
    public function preview(Carbon $on = null, Carbon $since = null, string $from = null): MessageCollection
    {
        try {
            $query = $this->withoutBody();

            if (filled($on)) {
                $query->whereOn($on);
            }

            if (filled($since)) {
                $query->whereSince($since);
            }

            if (filled($from)) {
                $query->whereFrom($from);
            }
            
            return $query->get()->mapWithKeys(fn(Message $message) => [
                $message->getMessageId()->first() => [
                    'subject'     => $message->getSubject()->first(),
                    'fromName'    => $message->getFrom()->first()->personal,
                    'fromAddress' => $message->getFrom()->first()->mail,
                    'date'        => $message->getDate()->first()
                ]
            ]);
        } catch (Throwable $e) {
            throw new ImapException($e->getMessage());
        }

    }

    /**
     * @throws ImapException
     */
    public function find(string $messageId): ?Message
    {
        try {
            // It is necessary to mark the message as read in order to get its attachments.
            $this->query()->whereMessageId($messageId)->markAsRead()->get();

            return $this->query()->whereMessageId($messageId)->get()->first();
        } catch (Throwable $e) {
            throw new ImapException($e->getMessage());
        }
    }

    /**
     * @return Folder
     * @throws ImapException
     */
    private function folder(): Folder
    {
        try {
            if (!isset($this->client)) {
                $this->client = Client::account($this->account);
                $this->client->connect();
            }

            return $this->client->getFolderByName($this->folderName);
        } catch (Throwable $e) {
            throw new ImapException($e->getMessage());
        }
    }
}