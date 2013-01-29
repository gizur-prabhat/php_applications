<?php
/* * *******************************************************************************
 * * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 * ****************************************************************************** */
///////////////////////  Added By Anil Singh ////////////////
$helpdeskrmfieldskey = array('1', '2', '3', '4', '6', '8', '10');
///////////////////////////////////////
?>
<input align="left" class="crmbutton small cancel"type="button" value="<?PHP echo getTranslatedString('LBL_BACK_BUTTON'); ?>" onclick="window.history.back();"/>	
</td>

<?PHP
global $result;
global $client;
global $Server_Path;
$customerid = $_SESSION['customer_id'];
$sessionid = $_SESSION['customer_sessionid'];
$block = "HelpDesk";
if ($ticketid != '') {

    $params = array('id' => "$ticketid", 'block' => "$block", 'contactid' => $customerid, 'sessionid' => "$sessionid");
    $result = $client->call('get_details', $params, $Server_Path, $Server_Path);
    // Check for Authorization
    if (count($result) == 1 && $result[0] == "#NOT AUTHORIZED#") {
        echo '<tr><td colspan="6" align="center"><b>' .
        getTranslatedString('LBL_NOT_AUTHORISED') .
        '</b></td></tr></table></td></tr></table></td></tr></table>';
        include("footer.html");
        die();
    }

    //////////////////////////////////////////////  Added By Anil Singh  For Remove field in above array.
    for ($i = 0; $i <= count($helpdeskrmfieldskey); $i++) {
        //$key=remove_item_by_value($result[0]['HelpDesk'],$helpdeskrmfields[$i],'fieldlabel');
        if (array_key_exists($helpdeskrmfieldskey[$i], $result[0]['HelpDesk'])) {
            unset($result[0]['HelpDesk'][$helpdeskrmfieldskey[$i]]);
            // $result[0]['HelpDesk']=array_values($result[0]['HelpDesk']);
        }
    }
    //////////////////////////////////////////////////// End
    $ticketinfo = $result[0][$block];

    //////////////////////////////////////////////////// End

    $params = Array(
        Array('id' => "$customerid",
            'sessionid' => "$sessionid",
            'ticketid' => "$ticketid")
    );
    $commentresult = $client->call('get_ticket_comments', $params, $Server_Path, $Server_Path);
    $ticketscount = count($result);
    $commentscount = count($commentresult);
    $params = Array(Array('id' => "$customerid", 'sessionid' => "$sessionid", 'ticketid' => "$ticketid"));

    //Get the creator of this ticket
    $creator = $client->call('get_ticket_creator', $params, $Server_Path, $Server_Path);

    $ticket_status = '';
    foreach ($ticketinfo as $key => $value) {
        $fieldlabel = $value['fieldlabel'];
        $fieldvalue = $value['fieldvalue'];
        if ($fieldlabel == 'Status') {
            $ticket_status = $fieldvalue;
            break;
        }
    }
    //If the ticket is created by this customer and status is not 
    //Closed then allow him to Close this ticket otherwise not
    if ($ticket_status != 'Closed' && $ticket_status != '') {
        $ticket_close_link = '<a href=index.php?module=HelpDesk&action=index&' .
            'fun=close_ticket&ticketid=' . $ticketid . '><b>' .
            getTranslatedString('LBL_CLOSE_TICKET') . '</b></a>&nbsp;&nbsp;&nbsp;';
    } else {
        $ticket_close_link = '';
    }

    echo getblock_header('Ticket Information', '4', $ticket_close_link);
    echo getblock_fieldlist($ticketinfo);
    if ($commentscount >= 1 && is_array($commentresult)) {

        $list .= '
		   <tr><td colspan="5" class="detailedViewHeader"><b>' . 
            getTranslatedString('LBL_TICKET_COMMENTS') . '</b></td></tr>
		   <tr>
			<td colspan="4" class="dvtCellInfo">
			   <div id="scrollTab2">
				<table width="100%"  border="0" cellspacing="5" cellpadding="5">';

        //Form the comments in between tr tags
        for ($j = 0; $j < $commentscount; $j++) {
            $list .= '<tr><td width="5%" valign="top">' . 
                ($commentscount - $j) . '</td><td width="95%">' . 
                $commentresult[$j]['comments'] . '<br><span class="hdr">' . 
                getTranslatedString('LBL_COMMENT_BY') . ' : ' . 
                $commentresult[$j]['owner'] . ' ' . 
                getTranslatedString('LBL_ON') . ' ' . 
                $commentresult[$j]['createdtime'] . '</span></td></tr>';
        }

        $list .= '</table></div></td></tr>';
    }


    if ($ticket_status != 'Closed') {
        $list .= '
		   <tr><td colspan="4" class="detailedViewHeader"><b>' . 
            getTranslatedString('LBL_ADD_COMMENT') . '</b></td></tr>
		   
		   <tr>
			<td colspan="4" class="dvtCellInfo"><form name="comments" action="index.php" method="post">
		   <input type="hidden" name="module"/>
		   <input type="hidden" name="action"/>
		   <input type="hidden" name="fun"/>
		   <input type=hidden name=ticketid value=' . $ticketid . '/>
           <textarea name="comments" 
            cols="55" rows="5" class="detailedViewTextBox"  
            onFocus="this.className=\'detailedViewTextBoxOn\'" 
            onBlur="this.className=\'detailedViewTextBox\'"></textarea></td>
		   <input class="crmbutton small cancel" title="' . 
            getTranslatedString('LBL_SUBMIT') . '" accesskey="S" class="small"  
                name="submit" value="' . getTranslatedString('LBL_SUBMIT') . 
            '" style="width: 70px;" type="submit" 
                onclick="this.form.module.value=\'HelpDesk\';this.form.action.value=\'index\';this.form.fun.value=\'updatecomment\'; if(trim(this.form.comments.value) != \'\')	return true; else return false;"/></form></td>
		   
			    <td colspan="2">&nbsp;</td>
			    <td>&nbsp;</td>
		   </tr>';
    }
    $files_array = getTicketAttachmentsList($ticketid);
    if ($files_array[0] != "#MODULE INACTIVE#") {
        $list .= '<!--  Added for Attachment -->
			   <tr><td colspan=4>&nbsp;</td></tr>
			   <tr><td colspan="4" class="detailedViewHeader"><b>' . 
            getTranslatedString('LBL_ATTACHMENTS') . '</b></td></tr>';

        //Get the attachments list and form in the tr tag
        $attachments_count = count($files_array);
        if (is_array($files_array)) {
            for ($j = 0; $j < $attachments_count; $j++) {
                $filename = $files_array[$j]['filename'];
                $filetype = $files_array[$j]['filetype'];
                $filesize = $files_array[$j]['filesize'];
                $fileid = $files_array[$j]['fileid'];
                $filelocationtype = $files_array[$j]['filelocationtype'];
                //To display the attachments title
                $attachments_title = '';
                if ($j == 0)
                    $attachments_title = '<b>' . 
                    getTranslatedString('LBL_ATTACHMENTS') . '</b>';
                if ($filelocationtype == 'I') {
                    $list .= '<tr><td class="dvtCellLabel" align="right">' . 
                        $attachments_title . '</td>
						<td class="dvtCellInfo" colspan="3">
                        <a href="index.php?downloadfile=true&fileid=' . 
                        $fileid . '&filename=' . $filename . '&filetype=' . 
                        $filetype . '&filesize=' . $filesize . '&ticketid=' . 
                        $ticketid . '">' . ltrim($filename, $ticketid . '_') . 
                        '</a></td></tr>';
                } else {
                    $list .='<tr><td class ="dvtCellLabel" align="right">' . 
                        $attachments_title . '</td>' .
                        '<td class="dvtCellInfo" colspan="3"><a target="blank" 
                            href=' . $filename . '>' . $filename . '</a></td>' .
                        '</tr>';
                }
            }
        } else {
            $list .= '<tr><td>' . getTranslatedString('NO_ATTACHMENTS') . '</td></tr>';
        }
    }
    //To display the file upload error
    if ($upload_status != '') {
        $list .= '<tr>
				<td class="dvtCellLabel" align="right"><b>' . getTranslatedString('LBL_FILE_UPLOADERROR') . '</b></td>
				<td class="dvtCellInfo" colspan="3"><font color="red">' . $upload_status . '</font></td>
			   </tr>';
    }

    //Provide the Add Comment option if the ticket is not Closed
    if ($ticket_status != 'Closed' && $files_array[0] != "#MODULE INACTIVE#") {
        //To display the File Browse option to upload attachment	
        $list .= '
		   <tr>
			<form name="fileattachment" method="post" enctype="multipart/form-data" action="index.php">
			<input type="hidden" name="module" value="HelpDesk">
			<input type="hidden" name="action" value="index">
			<input type="hidden" name="fun" value="uploadfile">
			<input type="hidden" name="ticketid" value="' . $ticketid . '">
				<td class="dvtCellLabel" align="right">' . getTranslatedString('LBL_ATTACH_FILE') . '</td>
				<td colspan=2 class="dvtCellInfo"><input type="file" size="50" name="customerfile" class="detailedViewTextBox" onchange="validateFilename(this)" />
				<input type="hidden" name="customerfile_hidden"/></td> 
				<td class="dvtCellInfo"><input class="crmbutton small cancel" name="Attach" type="submit" value="' . getTranslatedString('LBL_ATTACH') . '"></td>
			</form>
		   </tr>';
    }

    $list .= '
		</table>
	 </td>
   </tr>';


    echo $list;
    echo '</table></td></tr>'; //</td></tr></table>
    echo '<!-- --End--  -->';
}
else
    echo getTranslatedString('LBL_NONE_SUBMITTED');


$filevalidation_script = <<<JSFILEVALIDATION
<script type="text/javascript">
                
function getFileNameOnly(filename) {
	var onlyfilename = filename;
  	// Normalize the path (to make sure we use the same path separator)
 	var filename_normalized = filename.replace(/\\\\/g, '/');
  	if(filename_normalized.lastIndexOf("/") != -1) {
    	onlyfilename = filename_normalized.substring(filename_normalized.lastIndexOf("/") + 1);
  	}
  	return onlyfilename;
}
/* Function to validate the filename */
function validateFilename(form_ele) {
if (form_ele.value == '') return true;
	var value = getFileNameOnly(form_ele.value);
	// Color highlighting logic
	var err_bg_color = "#FFAA22";
	if (typeof(form_ele.bgcolor) == "undefined") {
		form_ele.bgcolor = form_ele.style.backgroundColor;
	}
	// Validation starts here
	var valid = true;
	/* Filename length is constrained to 255 at database level */
	if (value.length > 255) {
		alert(alert_arr.LBL_FILENAME_LENGTH_EXCEED_ERR);
		valid = false;
	}
	if (!valid) {
		form_ele.style.backgroundColor = err_bg_color;
		return false;
	}
	form_ele.style.backgroundColor = form_ele.bgcolor;
	form_ele.form[form_ele.name + '_hidden'].value = value;
	return true;
}
</script>
JSFILEVALIDATION;

echo $filevalidation_script;
?>
