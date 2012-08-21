<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
	
	function vtws_logincustomer($username,$pwd){
                $userId = vtws_verifyActiveCustomerPortalUser($username, $pwd);
                if ($userId != null) {
                    $accountId = vtws_getCustomerPortalUserAccount($userId);
                    $accessKeyAndUsername = vtws_getAccessKeyAndUsernameFromAccount($accountId);
                    return $accessKeyAndUsername;
                } else {
                    throw new WebServiceException(WebServiceErrorCode::$INVALIDUSERPWD,"Invalid username or password");
                }
	}
	
	function vtws_verifyActiveCustomerPortalUser($username, $password){
		global $adb;

                $sql = "select * from vtiger_portalinfo where user_name=? and user_password=?";
		$result = $adb->pquery($sql,array($username, $password));

                if($result != null && isset($result)){
			if($adb->num_rows($result)>0){
				return $adb->query_result($result,0);
			}
		}
		return null;
	}
	
	function vtws_getCustomerPortalUserAccount($userId){
		global $adb;
		
		$sql = "SELECT accountid FROM `vtiger_contactdetails` WHERE contactid=?";
		$result = $adb->pquery($sql,array($userId));
		if($result != null && isset($result)){
			if($adb->num_rows($result)>0){
				return $adb->query_result($result,0);
			}
		}
		return null;
	}
	
	function vtws_getAccessKeyAndUsernameFromAccount($accountId){
		global $adb;
                
                //Get the Object Type Id for Accounts Module
		$sql = "SELECT id FROM `vtiger_ws_entity` WHERE name=?";
		$result = $adb->pquery($sql,array('Accounts'));
		if($result != null && isset($result)){
                    $objectTypeId = $adb->query_result($result, 0, 'id');
                }                
		
		$sql = "SELECT smownerid FROM `vtiger_crmentity` WHERE crmid=?";
		$result = $adb->pquery($sql,array($accountId));
		if($result != null && isset($result)){
			if($adb->num_rows($result)>0){
				$vtigerUserId = $adb->query_result($result,0);
                                $sql = "SELECT user_name, accesskey FROM `vtiger_users` WHERE id=?";
                                $result = $adb->pquery($sql,array($vtigerUserId));
                                if($result != null && isset($result)){
                                        if($adb->num_rows($result)>0){
                                                return array('accountId' => $objectTypeId . "x" . $accountId) + array_intersect_key($adb->query_result_rowdata($result,0), array_flip(array('accesskey','user_name')));
                                        }
                                }
			}
		}
		return null;
	}        
?>