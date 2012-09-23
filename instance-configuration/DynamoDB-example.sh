#!/usr/bin/php

<?php
/**
 * Yii Controller to handel REST queries
 *
 * Works with remote vtiger REST service
 *
 * @package        	GizurCloud
 * @subpackage    	Instance-configuration
 * @category    	Shell Script
 * @author        	Jonas Colmsjö
 **/

/*
 * Including Amazon classes
 */



require_once('../lib/aws-php-sdk/sdk.class.php');


// JUST TESTING
$_GET['email'] = 'clab@gizur.com';

// Instantiate the class
$dynamodb = new AmazonDynamoDB();
$dynamodb->set_region(AmazonDynamoDB::REGION_EU_W1); 
$table_name = 'GIZUR_ACCOUNTS';

// Get an item
$ddb_response = $dynamodb->get_item(array(
    'TableName' => $table_name,
    'Key' => $dynamodb->attributes(array('HashKeyElement'  => $_GET['email'], )),
    'ConsistentRead' => 'true'
));
        
if (isset($ddb_response->body->Item)) {
    foreach($ddb_response->body->Item->children() as $key => $item) {
        $result->{$key} = (string)$item->{AmazonDynamoDB::TYPE_STRING};
    }

    $response->success = true;
    $response->result = $result;

	//$this->_sendResponse(200, json_encode($response));
	// printing, just for testing purposes
	print json_encode($response);

} else {
    $response->success = false;
    $response->error->code = "NOT_FOUND";
	$response->error->message = $_GET['email'] . " was " . " not found";

	//$this->_sendResponse(404, json_encode($response));      
	// printing, just for testing purposes
	print json_encode($response);
}



include("gc1-ireland/rest-api/config.inc.php");
require_once 'MDB2.php';

/**
 *  The Pear PHP dagtabase API MDB2 will is used
 *  http://pear.php.net/manual/en/package.database.mdb2.php
 */


/**
 * Database connection string
 * @global string $dsn
 *
 * Example 'mysql://root:mysecret@localhost/mysql'
 */
$dsn = "mysql://" . $dbconfig['db_username'] . ":" . $dbconfig['db_password'] . "@" . $dbconfig['db_server'] . $dbconfig['db_port'] . "/" . $dbconfig['db_name'];


/**
 * Database connection options
 * @global string $options
 */
$options = array(
    'persistent' => true,
);

/**
 * Database MDB2 connection object 
 * @global mixed $mdb2
 */
$mdb2 =& MDB2::factory($dsn, $options);

if (PEAR::isError($mdb2)) {
    echo ($mdb2->getMessage().' - '.$mdb2->getUserinfo());
}


/**
 * Create the saleorder_interface table 
 *
 * @param mixed $mdb2
 * @return int
 */
function createTable($mdb2) {

   /**
    * First drop the table if it exists
    */
    $query = <<<EOT
        DROP TABLE IF EXISTS `salesorder_interface` ;
EOT;

    // Execute the query
    $result = $mdb2->exec($query);

    // check if the query was executed properly
    if (PEAR::isError($result)) {
        echo ($result->getMessage().' - '.$result->getUserinfo());
        exit();
    }
    
    /**
    * First drop the table if it exists
    */
    
    $query2 = <<<EOT
        DROP TABLE IF EXISTS `saleorder_msg_que` ;
EOT;

    // Execute the query
    $result = $mdb2->exec($query2);

    // check if the query was executed properly
    if (PEAR::isError($result)) {
        echo ($result->getMessage().' - '.$result->getUserinfo());
        exit();
    }

   /**
    * Then create the table
    */
    $query = <<<EOT
        CREATE TABLE `salesorder_interface` (
                     `id` int(19) NOT NULL AUTO_INCREMENT,
                     `salesorderid` int(19) NOT NULL DEFAULT '0',
                     `salesorder_no` varchar(100) DEFAULT NULL,
                     `contactid` int(19) DEFAULT NULL,
                     `productname` varchar(100) DEFAULT NULL,
                     `productid` int(11) DEFAULT NULL,
                     `productquantity` int(5) DEFAULT NULL,
                     `duedate` date DEFAULT NULL,
                     `featurdate` date DEFAULT NULL,
                     `accountname` varchar(100) DEFAULT NULL,
                     `accountid` int(19) DEFAULT NULL,
                     `sostatus` varchar(200) DEFAULT NULL,
                     `batchno` varchar(20) NOT NULL,
                     `createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
EOT;

    // Execute the query
    $result = $mdb2->exec($query);

    // check if the query was executed properly
    if (PEAR::isError($result)) {
        echo ($result->getMessage().' - '.$result->getUserinfo());
        exit();
    }
    
    /**
    * Then create the table saleorder_msg_que
    */
    $query2 = <<<EOT
        CREATE TABLE `saleorder_msg_que` (
                     `id` int(19) NOT NULL AUTO_INCREMENT,
                     `accountname` varchar(100) DEFAULT NULL,
                     `ftpfilename` varchar (200) DEFAULT NULL,
                     `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                     `status` int(1) DEFAULT '0',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
EOT;

    // Execute the query
    $result = $mdb2->exec($query2);

    // check if the query was executed properly
    if (PEAR::isError($result)) {
        echo ($result->getMessage().' - '.$result->getUserinfo());
        exit();
    }
   

    // Disconnect from the database
    $mdb2->disconnect();

    return 0;
}

/**
 * Create database and user 
 *
 * @param mixed $mdb2
 * @param mixed $username
 * @param mixed $password
 * @return int
 */
function createUser($mdb2, $username, $password) {

   // CREATE DATABASE IF NOT EXISTS $username
   // CREATE USER $username . $dbconfig['db_server'] . $dbconfig['db_port']  IDENTIFIED BY $password; 
    //   DROP TABLE IF EXISTS `salesorder_interface` ;


   /**
    * Create database
    */
    $query = <<<EOT
        CREATE DATABASE IF NOT EXISTS `$username` ;
EOT;

    // Execute the query
    $result = $mdb2->exec($query);

    // check if the query was executed properly
    if (PEAR::isError($result)) {
        echo ($result->getMessage().' - '.$result->getUserinfo());
        exit();
    }
    

    /**
     * Create user
     */
    $tmp = $username  . "@" . $dbconfig['db_server'] . $dbconfig['db_port'];

   $query = <<<EOT
       CREATE USER $tmp IDENTIFIED BY $password;
EOT;

    // Execute the query
    $result = $mdb2->exec($query);

    // check if the query was executed properly
    if (PEAR::isError($result)) {
        echo ($result->getMessage().' - '.$result->getUserinfo());
        exit();
    }

 
    // Disconnect from the database
    $mdb2->disconnect();

    return 0;
}

// create user
$result = createUser($mdb2, 'test2', 'test1');
print "User created successfully!\n";

// create table
// $result = createTable($mdb2);
//print "Table created successfully!\n";

?>

