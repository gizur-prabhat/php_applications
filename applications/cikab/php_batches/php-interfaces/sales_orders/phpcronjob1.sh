#!/usr/bin/php
<?php
/**
 * @category   Cronjobs
 * @package    Integration
 * @subpackage CronJob
 * @author     Prabhat Khera <prabhat.khera@essindia.co.in>
 * @version    SVN: $Id$
 * @link       href="http://gizur.com"
 * @license    Commercial license
 * @copyright  Copyright (c) 2012, Gizur AB, 
 * <a href="http://gizur.com">Gizur Consulting</a>, All rights reserved.
 *
 * purpose : Connect to Amazon SQS through aws-php-sdk
 * Coding standards:
 * http://pear.php.net/manual/en/standards.php
 *
 * PHP version 5.3
 *
 */
/*
 * Load configuration files
 */
require_once __DIR__ . '/../config.inc.php';

class PhpBatchOne
{

    private $vTigerConnect;
    private $integrationConnect;
    private $messages = array();
    private $basProductID;
    
    public function __construct()
    {
        $this->basProductID = Config::$customFields['basProductId'];
        
        openlog(
            "phpcronjob1", LOG_PID | LOG_PERROR, LOG_LOCAL0
        );

        /*
         * Trying to connect to vTiger database
         */
        syslog(
            LOG_INFO, "Trying to connect to vTiger database"
        );

        $this->vTigerConnect = new mysqli(
            Config::$dbVtiger['db_server'], 
            Config::$dbVtiger['db_username'], 
            Config::$dbVtiger['db_password'], 
            Config::$dbVtiger['db_name'], 
            Config::$dbVtiger['db_port']
        );

        if ($this->vTigerConnect->connect_errno)
            throw new Exception('Unable to connect with vTiger DB');

        syslog(
            LOG_INFO, "Connected to vTiger database"
        );

        syslog(
            LOG_INFO, "Trying to connect to integration database"
        );

        /*
         * Trying to connect to integration database
         */
        $this->integrationConnect = new mysqli(
            Config::$dbIntegration['db_server'], 
            Config::$dbIntegration['db_username'], 
            Config::$dbIntegration['db_password'], 
            Config::$dbIntegration['db_name'], 
            Config::$dbIntegration['db_port']
        );

        if ($this->integrationConnect->connect_errno)
            throw new Exception('Unable to connect with integration DB');
        
        syslog(
            LOG_INFO, "Connected with integration db"
        );
    }

    protected function getSalesOrders()
    {
        syslog(LOG_INFO, "In getSalesOrders() : Preparing sales order query");
        
        $salesOrdersQuery = "SELECT SO.salesorderid, SO.salesorder_no, 
            ACCF.cf_664, ACCF.cf_665, ACCO.accountname, SO.contactid,
            SO.duedate, SO.sostatus, ACCO.accountid
            FROM vtiger_salesorder SO 
            LEFT JOIN vtiger_account ACCO on ACCO.accountid = SO.accountid
            LEFT JOIN vtiger_accountscf ACCF on ACCF.accountid = ACCO.accountid
            WHERE SO.sostatus IN ('Created','Approved')
            AND (ACCF." . Config::$customFields['setFiles'] .
            " = 'Yes' OR ACCF." . Config::$customFields['mosFiles'] .
            " = 'Yes') LIMIT 0, " . Config::$batchVariable;

        syslog(
            LOG_INFO,
            "In getSalesOrders() : Executing Query: " . $salesOrdersQuery
        );

        $salesOrders = $this->vTigerConnect->query($salesOrdersQuery);

        if (!$salesOrders) {            
            syslog(
                LOG_WARNING, 
                "In getSalesOrders() : Error executing sales order query :" .
                " ({$this->vTigerConnect->errno}) - " .
                "{$this->vTigerConnect->error}"
            );
            throw new Exception(
                "In getSalesOrders() : Error executing sales order query : " .
                "({$this->vTigerConnect->errno}) - " .
                "{$this->vTigerConnect->error}"
            );
        }

        if ($salesOrders->num_rows == 0) {
            syslog(
                LOG_WARNING, "In getSalesOrders() : No Sales Order Found!"
            );
            throw new Exception("In getSalesOrders() : No Sales Order Found!");
        }

        return $salesOrders;
    }

    protected function getProductsBySalesOrders($salesOrderNo)
    {
        /*
         * Fetch current sales order products.
         */
        $salesOrderProducts = $this->vTigerConnect->query(
            "SELECT " .
            "SO.salesorderid, SO.salesorder_no, SO.contactid," .
            "SO.duedate, SO.sostatus, ACCO.accountname, " .
            "ACCO.accountid, PRO.productid, " .
            "PRO.productname, IVP.quantity, " .
            "PCF." . Config::$customFields['basProductId'] . " " .
            "FROM vtiger_salesorder SO " .
            "INNER JOIN vtiger_account ACCO on ACCO.accountid = SO.accountid " .
            "INNER JOIN vtiger_inventoryproductrel IVP on IVP.id = " .
            "SO.salesorderid " .
            "INNER JOIN vtiger_products PRO on PRO.productid = IVP.productid " .
            "INNER JOIN vtiger_productcf PCF ON PRO.productid = PCF.productid " .
            "WHERE SO.salesorder_no = '$salesOrderNo'"
        );

        syslog(
            LOG_INFO, "Total number of products ($salesOrderNo): " .
            $salesOrderProducts->num_rows
        );

        return $salesOrderProducts;
    }

    protected function createIntegrationSalesOrder($salesOrder)
    {
        $batchNo = $salesOrder->salesorder_no . '-' . Config::$batchVariable;

        syslog(
            LOG_INFO, "In createIntegrationSalesOrder() " .
            "Preparing insert sales order query ($salesOrder->salesorder_no)"
        );
        /*
         * Insert sales order into integration table.
         */
        $interfaceQuery = $this->integrationConnect->query(
            "INSERT INTO sales_orders
            SET `id` = NULL,
            `salesorderid` = $salesOrder->salesorderid,
            `salesorder_no` = '$salesOrder->salesorder_no',
            `contactid` = $salesOrder->contactid,
            `duedate` = '$salesOrder->duedate',
            `accountname` = '$salesOrder->accountname',
            `accountid` = $salesOrder->accountid,
            `batchno` = '$batchNo', 
            `set` = '" . $salesOrder->{Config::$customFields['setFiles']} . "',
            `mos` = '" . $salesOrder->{Config::$customFields['mosFiles']} . "',
            `set_status` = '$salesOrder->sostatus',
            `mos_status` = '$salesOrder->sostatus',
            `created` = now()"
        );

        syslog(
            LOG_INFO, 
            "In createIntegrationSalesOrder() : " .
            "Inserting sales order: " . $interfaceQuery
        );

        /*
         * If insertion failed, Close resultset and raise exception.
         */
        if (!$interfaceQuery) {
            syslog(
                LOG_WARNING, 
                "Error inserting salesorder $salesOrder->salesorder_no in " .
                "integration db ({$this->integrationConnect->errno}) - " .
                "{$this->integrationConnect->error}"
            );
            throw new Exception(
                "Error inserting salesorder $salesOrder->salesorder_no in " .
                "integration db ({$this->integrationConnect->errno}) - " .
                "$this->integrationConnect->error"
            );
        }

        return $interfaceQuery;
    }

    protected function createIntegrationProduct($salesOrderId, $salesOrderProduct)
    {
        syslog(
            LOG_INFO, 
            "In createIntegrationProduct($salesOrderId)" .
            " Preparing insert product query.: "
        );
        /*
         * Insert sales order into integration table.
         */
        $interfaceQuery = $this->integrationConnect->query(
            "INSERT INTO sales_order_products
            SET id = NULL, 
            productname = '$salesOrderProduct->productname',
            productid = $salesOrderProduct->productid,
            productquantity = $salesOrderProduct->quantity,
            featurdate = NULL,
            sales_order_id = $salesOrderId,
            bas_product_id = '$salesOrderProduct->{$this->basProductID}',
            created = now()"
        );

        syslog(
            LOG_INFO, 
            "In createIntegrationProduct($salesOrderId) " .
            "Inserting product: " . $interfaceQuery
        );

        /*
         * If insertion failed, Close resultset and raise exception.
         */
        if (!$interfaceQuery) {
            syslog(
                LOG_WARNING, 
                "Error inserting product $salesOrderProduct->productname in " .
                "integration db ({$this->integrationConnect->errno}) - " .
                "{$this->integrationConnect->error}"
            );
            throw new Exception(
                "Error inserting product $salesOrderProduct->productname in " .
                "integration db ({$this->integrationConnect->errno}) - " .
                "{$this->integrationConnect->error}"
            );
        }

        return $interfaceQuery;
    }

    protected function updateVtigerSalesOrder($salesOrderID, $status = 'Delivered')
    {
        syslog(
            LOG_INFO, "Updating sales order ($salesOrderID) $status"
        );

        $updateSaleOrder = $this->vTigerConnect->query(
            "UPDATE vtiger_salesorder SET " .
            "sostatus = '$status' WHERE salesorderid = " .
            "'$salesOrderID'"
        );

        if (!$updateSaleOrder) {
            syslog(
                LOG_WARNING,
                "In updateVtigerSalesOrder($salesOrderID, $status) : " .
                "Error updating salesorder"
            );
            throw new Exception(
                "In updateVtigerSalesOrder($salesOrderID, $status) : " .
                "Error updating salesorder"
            );
        }
        
        return $updateSaleOrder;
    }

    public function init()
    {
        try {
            $salesOrders = $this->getSalesOrders();
            $numberSalesOrders = $salesOrders->num_rows;
            
            /*
             * Update message array with number of sales orders.
             */
            $this->messages['count'] = $numberSalesOrders;
            $msg = &$this->messages['salesorders'];
            
            while ($salesOrder = $salesOrders->fetch_object()) {
                try {
                    /*
                     * Disable auto commit.
                     */
                    syslog(
                        LOG_INFO, "Disabling auto commit"
                    );
                    $this->vTigerConnect->autocommit(FALSE);
                    $this->integrationConnect->autocommit(FALSE);

                    $this->createIntegrationSalesOrder($salesOrder);

                    $soId = $this->integrationConnect->insert_id;

                    $salesOrderProducts = $this->getProductsBySalesOrders(
                        $salesOrder->salesorder_no
                    );
                    
                    $msg[$salesOrder->salesorder_no]['count']
                        = $salesOrderProducts->num_rows;
                    $msgP = &$msg[$salesOrder->salesorder_no]['products'];
                    
                    $flag = true;

                    while ($flag && $salesOrderProduct = $salesOrderProducts->fetch_object()) {
                        $msgP[$salesOrderProduct->productname]['qtn']
                            = $salesOrderProduct->quantity;
                        
                        $flag = $flag && $this->createIntegrationProduct(
                                $soId, $salesOrderProduct
                        );
                        
                        $msgP[$salesOrderProduct->productname]['status'] = true;
                    }

                    if ($flag)
                        $this->updateVtigerSalesOrder(
                            $salesOrder->salesorderid,
                            'Delivered'
                        );
                        
                    $msg[$salesOrder->salesorder_no]['status'] = true;
                    /*
                     * Commit the databases.
                     */
                    $this->integrationConnect->commit();
                    $this->vTigerConnect->commit();
                    
                } catch (Exception $e) {
                    $numberSalesOrders--;
                    /*
                     * Store the messages
                     */
                    $msgP[$salesOrderProduct->productname]['error'] 
                        = $e->getMessage();
                    $msgP[$salesOrderProduct->productname]['status'] = false;
                    /*
                     * Rollback the connections
                     */
                    $this->integrationConnect->rollback();
                    $this->vTigerConnect->rollback();
                }
            }
            
            $this->messages['message'] = "$numberSalesOrders number " .
                        "of sales orders processed.";
            
        } catch (Exception $e) {
            /*
             * Store the message and rollbach the connections.
             */
            $this->messages['message'] = $e->getMessage();
            /*
             * Rollback the connections
             */
            $this->integrationConnect->rollback();
            $this->vTigerConnect->rollback();
        }
        
        syslog(
            LOG_INFO, 
            json_encode($this->messages)
        );
        echo json_encode($this->messages);
    }

}

try{
    $phpBatchOne = new PhpBatchOne();
    $phpBatchOne->init();
}catch(Exception $e){
    syslog(LOG_WARNING, $e->getMessage());
    echo $e->getMessage();
}