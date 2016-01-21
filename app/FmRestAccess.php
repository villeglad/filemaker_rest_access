<?php

namespace App;

use Soliant\SimpleFM\Adapter;
use Soliant\SimpleFM\HostConnection;
use Soliant\SimpleFM\Result\FmResultSet;

class FmRestAccess
{
    protected $host;
    protected $db;
    protected $account;
    protected $password;

    protected $adapter;

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function getDb()
    {
        $this->db;
    }

    public function makeAdapter($host, $db, $account, $password)
    {
        $this->host = $host;
        $this->account = $account;
        $this->password = $password;
        $this->db = $db;

        $this->adapter = new Adapter($this->makeConnection());
    }

    private function makeConnection()
    {
        return $hostConnection = new HostConnection(
            $this->host,
            $this->db,
            $this->account,
            $this->password
        );
    }
}

