<?php

namespace Database;
use \PDO as PDO;
use \Exception as Exception;

/**
 * Class provides mysql PDO objects to databases. Implement the Singleton (?anti-?)pattern.
 */
class Homepage extends Database {

	/**
	 * Sets the databse and user information for the Tools database.
	 */
	protected function init() {
		$this->host = getenv('HOMEPAGE_DB_HOST');
		$this->dbname = getenv('HOMEPAGE_DB_NAME');
        $this->user = getenv('HOMEPAGE_DB_USER');
        $this->password = getenv('HOMEPAGE_DB_PASSWORD');
	}
}