<?php namespace la\core\db;
if ( ! defined( 'WPINC' ) ) die;

use Exception;
use la\core\db\migrations\ILADBMigration;
use la\core\LAUtils;
use ReflectionClass;
use ReflectionException;

/**
 * Flow-Flow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>

 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
abstract class LADBMigrationManager{
    const INIT_MIGRATION = '0.9999';

    protected $context;

    public function __construct($context) {
        $this->context = $context;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public final function migrate(){
        $version = $this->getDBVersion();
        if (!$this->hasMigrations4Perform($version)){
            return;
        }

        $dbm = LAUtils::dbm($this->context);
        $conn = $this->connection();
        try{
            if ( $conn->autocommit(false) ){
                if ($this->needStartInitMigration($version)){
                    foreach ($this->getInitMigration() as $max_version => $migration){
                        $migration->execute($conn, $dbm);
                        $dbm->setOption('db_version', $max_version);
                    }
                }
                else {
                    foreach ( $this->getMigrations() as $migration ) {
                        if ($this->needExecuteMigration($version, $migration->version())){
                            $migration->execute($conn, $dbm);
                            $dbm->setOption('db_version', $migration->version());
                        }
                    }
                }
                $conn->commit();
            }
        } catch ( Exception $e ){
            error_log($e->getTraceAsString());
            $conn->rollback();
            $conn->close();
            throw $e;
        }
    }

    /**
     * Return the list of migration
     * @return array
     */
    protected abstract function migrations();

    /**
     * @return LASafeMySQL
     */
    protected function connection() {
        return LAUtils::dbm($this->context)->conn();
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getDBVersion(){
        global $wpdb;
        $version = self::INIT_MIGRATION;
        $table = LAUtils::dbm($this->context)->option_table_name;
        if (null != $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table))){
            $option = LAUtils::slug_down($this->context) . '_db_version';
            $version = $wpdb->get_var($wpdb->prepare('select value from ' . $table . ' where id = %s', $option));
            if (null == $version){
                $e = new Exception('Can`t get the db version of plugin');
                error_log($e->getTraceAsString());
                return self::INIT_MIGRATION;
            }
        }
        return $version;
    }

    private function needStartInitMigration($version){
        return self::INIT_MIGRATION == $version || $version === false;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getInitMigration(){
        $migrations = $this->getMigrations();

        $max = self::INIT_MIGRATION;
        foreach ($migrations as $version => $migration) {
            if ($max < $version){
                $max = $version;
            }
        }
        return [ $max => $migrations[self::INIT_MIGRATION] ];
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getMigrations(){
        $migrations = [];
        foreach ($this->migrations() as $class) {
            $clazz = new ReflectionClass($class);
            /** @var ILADBMigration $migration */
            $migration = $clazz->newInstance();
            $migrations[$migration->version()] = $migration;
        }
        uksort($migrations, 'version_compare');

        return $migrations;
    }

    private function needExecuteMigration($db_version, $migration_version){
        $db = explode('.', $db_version);
        $migration = explode('.', $migration_version);
        if (intval($migration[0]) == intval($db[0])){
            return (intval($migration[1]) > $db[1]);
        }
        return (intval($migration[0]) > intval($db[0]));
    }

    /**
     * @param $version
     *
     * @return bool
     * @throws ReflectionException
     */
    private function hasMigrations4Perform($version){
        if ($this->needStartInitMigration($version)){
            return true;
        }
        foreach ( $this->getMigrations() as $migration ) {
            if ( $this->needExecuteMigration( $version, $migration->version() ) ) {
                return true;
            }
        }
        return false;
    }
}