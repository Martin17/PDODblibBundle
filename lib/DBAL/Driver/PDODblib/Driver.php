<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Paptuc\DBAL\Driver\PDODblib;

use Doctrine\DBAL\Platforms\SQLServer2005Platform;
use Doctrine\DBAL\Platforms\SQLServer2008Platform;
use Doctrine\DBAL\Platforms\SQLServer2012Platform;
use Doctrine\DBAL\Platforms\SQLServerPlatform;
use Doctrine\DBAL\Schema\SQLServerSchemaManager;
use Doctrine\DBAL\VersionAwarePlatformDriver;
use Paptuc\DBAL\Platforms\MsSqlPlatform;
use Paptuc\DBAL\Schema\PDODblibSchemaManager;

/**
 * The PDO-based Dblib driver.
 *
 * @since 2.0
 */
class Driver implements \Doctrine\DBAL\Driver, VersionAwarePlatformDriver
{
    public function connect(array $params, $username = null, $password = null, array $driverOptions = array())
    {
        return new Connection(
            $this->_constructPdoDsn($params),
            $username,
            $password,
            $driverOptions
        );
    }

    /**
     * Constructs the Dblib PDO DSN.
     *
     * @param array $params
     * @return string The DSN
     */
    private function _constructPdoDsn(array $params)
    {
        $dsn = 'dblib:host=';

        if (isset($params['host'])) {
            $dsn .= $params['host'];
        }

        if (isset($params['port']) && !empty($params['port'])) {
            $portSeparator = (PATH_SEPARATOR === ';') ? ',' : ':';
            $dsn .= $portSeparator . $params['port'];
        }

        if (isset($params['dbname'])) {
            $dsn .= ';dbname=' . $params['dbname'];
        }

        if (isset($params['charset'])) {
            $dsn .= ';charset=' . $params['charset'];
        }
        return $dsn;
    }

    public function getDatabasePlatform()
    {

        if (class_exists('\\Doctrine\\DBAL\\Platforms\\SQLServer2012Platform')) {
            return new SQLServer2012Platform();
        }

        if (class_exists('\\Doctrine\\DBAL\\Platforms\\SQLServer2008Platform')) {
            return new SQLServer2008Platform();
        }

        if (class_exists('\\Doctrine\\DBAL\\Platforms\\SQLServer2005Platform')) {
            return new SQLServer2005Platform();
        }

        if (class_exists('\\Doctrine\\DBAL\\Platforms\\SQLServerPlatform')) {
            return new SQLServerPlatform();
        }
        if (class_exists('\\Paptuc\\DBAL\\Platforms\\MsSqlPlatform')) {
            return new MsSqlPlatform();
        }
    }

    public function createDatabasePlatformForVersion($version)
    {
        $versionSplit = explode(".", $version, 2);
        $major = (int)$versionSplit[0];
        if ($major < 9) {
            return new SQLServerPlatform();
        }
        if ($major == 9) {
            return new SQLServer2005Platform();
        }
        if ($major == 10) {
            return new SQLServer2008Platform();
        }
        if ($major > 10) {
            return new SQLServer2012Platform();
        }
    }

    public function getSchemaManager(\Doctrine\DBAL\Connection $conn)
    {
        if (class_exists('\\Doctrine\\DBAL\\Schema\\SQLServerSchemaManager')) {
            return new SQLServerSchemaManager($conn);
        }

        if (class_exists('\\Doctrine\\DBAL\\Schema\\PDODblibSchemaManager')) {
            return new PDODblibSchemaManager($conn);
        }


    }

    public function getName()
    {
        return 'pdo_dblib';
    }

    public function getDatabase(\Doctrine\DBAL\Connection $conn)
    {
        $params = $conn->getParams();
        return $params['dbname'];
    }
}
