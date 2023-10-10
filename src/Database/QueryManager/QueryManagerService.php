<?php

declare(strict_types=1);

namespace SimPay\SimPayWordpressPlugin\Database\QueryManager;

class QueryManagerService implements QueryManagerInterface
{

    public function __construct(private \wpdb $wpDb)
    {
    }

    /**
     * @throws FailedDatabaseQueryException
     */
    public function write($query): int|bool
    {
        $dbResult = $this->wpDb->query($query);

        if ($dbResult === false) {
            throw new FailedDatabaseQueryException($this->getLastDbError());
        }

        return $dbResult;
    }

    private function getLastDbError(): string
    {
        ob_start();
        $this->wpDb->print_error();

        $error = ob_get_contents();
        ob_end_flush();

        return $error;
    }

    public function readOne(string $query): mixed
    {
        return $this->wpDb->get_row($query);
    }

    public function read(string $query): mixed
    {
        return $this->wpDb->get_results($query);
    }

    public function getDbPrefix(): string
    {
        return $this->wpDb->prefix;
    }

    public function getDbCharset(): string
    {
        return $this->wpDb->get_charset_collate();
    }
}
