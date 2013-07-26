<?php


/**
 * Yii Controller to handel REST queries
 *
 * Works with remote vtiger REST service
 *
 * PHP version 5
 *
 * @category   Shell_Script
 * @package    GizurCloud
 * @subpackage Instance-configuration
 * @author     Jonas Colmsjö <jonas@gizur.com>
 *
 * @license    Gizur Private License
 * @link       http://api.gizur.com/api/index.php
 **/

/**
 * configuration
 *
 * @category   Configuration
 * @package    GizurCloud
 * @subpackage Instance-configuration
 * @author     Jonas Colmsjö <jonas@gizur.com>
 *
 * @license    Gizur Private License
 * @link       http://api.gizur.com/api/index.php
*/

class configuration
{
    /**
    * get function 
    * 
    * @return array for configuration
    */

    public function get()
    {
        return array(
            'url' => 'http://phpapplications-
            env-sixmtjkbzs.elasticbeanstalk.com/api/',
            'GIZURCLOUD_API_KEY' =>
             'GZCLD51A309109FD3551A309109FE26',
            'GIZURCLOUD_SECRET_KEY' =>
             "51a309109fca42.1554128151a309109fcb20
            .84498075",
            'API_VERSION' => '0.1',
            'credentials' => array(
                'portal_user@gizur.com' => "2hxrftmd",
                //'mobile_user@gizur.com' => 'ivry34aq',
            ),
            'clientid' => 'clab'
        );
    }
    
    /**
    * getBikerPortalCredentials function 
    * 
    * @return array for getBikerPortalCredentials configuration
    */

    public function getBikerPortalCredentials()
    {
        return array(
            'url' =>
             'http://phpapplications-env-sixmtjkbzs.
             elasticbeanstalk.com/applications/cikab',
            'credentials' => array(
                array('user_name' => 'gizur-ess-prabhat@gizur.com',
                    'user_password' => 'essindia', 'id' => 2),
            ),
            'version' => '5.4.0'
        );
    }

}
