<?php

namespace Database;
use \PDO as PDO;
use \Exception as Exception;

/**
 * Class provides mysql PDO objects to databases. Implement the Singleton (?anti-?)pattern.
 *
 * @see extends the abstract database class.   See class for addtional functionality.
 */
class Directory extends Database{

	/**
	 * Sets the databse and user information for the Tools database.
	 */
	protected function init() {
		$this->host = getenv('DIRECTORY_DB_HOST');
		$this->dbname = getenv('DIRECTORY_DB_NAME');
        $this->user = getenv('DIRECTORY_DB_USER');
        $this->password = getenv('DIRECTORY_DB_PASSWORD');
	}
}