<?php

namespace Eshop\Services\Imap;

use Eshop\Services\Imap\Exceptions\ImapException;
use Throwable;
use Webklex\IMAP\Facades\Client;
use Webklex\PHPIMAP\Folder;
use Webklex\PHPIMAP\Message;
use Webklex\PHPIMAP\Query\WhereQuery;

class ImapService
{
    private string $account;
    private string $folderName;

    private Folder $folder;

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
            return $this->folder()
                ->query()
                ->whereAll()
                ->markAsRead();
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
        return $this->query();
    }

    /**
     * @throws ImapException
     */
    public function find(string $messageId): Message
    {
        try {
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
        return $this->connect();
    }

    /**
     * @throws ImapException
     */
    private function connect(): Folder
    {
        $client = Client::account($this->account);
        try {
            $client->connect();

            return $client->getFolderByName($this->folderName);
        } catch (Throwable $e) {
            throw new ImapException($e->getMessage());
        }
    }
}