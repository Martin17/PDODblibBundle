<?php

namespace Paptuc\DBAL\Driver\PDOODBC;

use Doctrine\DBAL\Driver\PDOStatement;

/**
 * PDOStatement extension for closing cursor after execute as ODBC expects
 */
class Statement extends PDOStatement implements \Doctrine\DBAL\Driver\Statement
{
    /**
     * Protected constructor.
     */
    protected function __construct()
    {
    }

    /**s
     * {@inheritdoc}
     */
    public function execute($params = null)
    {
        $this->closeCursor();

        return parent::execute($params);
    }

    /**
     * {@inheritdoc}
     */
    public function bindValue($name, $value, $type = \PDO::PARAM_STR)
    {
        $this->closeCursor();

        return parent::bindValue($name, $value, $type == \PDO::PARAM_BOOL ? \PDO::PARAM_INT : $type);
    }

    /**
     * {@inheritdoc}
     */
    public function bindParam($column, &$variable, $type = \PDO::PARAM_STR, $length = null, $driverOptions = null)
    {
        $this->closeCursor();

        return parent::bindParam($column, $variable, $type, $length, $driverOptions);
    }
}
