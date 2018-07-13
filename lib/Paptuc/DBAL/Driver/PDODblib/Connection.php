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

use Paptuc\DBAL\Driver\BaseConnection;

/**
 * PDODblib Connection implementation.
 *
 * @since 2.0
 */
class Connection extends BaseConnection  {
    /**
     * Connection constructor.
     */
    public function __construct($dsn, $user = null, $password = null, array $options = null)
    {
        try {
            parent::__construct($dsn, $user, $password, $options);

            $this->exec("SET ANSI_WARNINGS ON");
            $this->exec("SET ANSI_PADDING ON");
            $this->exec("SET ANSI_NULLS ON");
            $this->exec("SET QUOTED_IDENTIFIER ON");
            $this->exec("SET CONCAT_NULL_YIELDS_NULL ON");
        } catch (\PDOException $exception) {
            self::pdoException($exception);
        }
    }
}
