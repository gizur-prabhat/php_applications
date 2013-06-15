
/**
 * This file contains routing function used throughout Gizur SaaS.
 *
 * @package    Gizur SaaS
 * @subpackage config
 * @author     Prabhat Khera <prabhat.khera@essindia.co.in>
 * @version    SVN: $Id$
 *
 * @license    Commercial license
 * @copyright  Copyright (c) 2012, Gizur AB, 
 * <a href="http://gizur.com">Gizur Consulting</a>, All rights reserved.
 *
 * JavaScript
 *
 */

// Config
// =======
// 
// This file stores all the configuration needed through-out
// the application.

// Server URL
// ==========
// This is where all request will be made.
// This URL is actually, where this apllication will be host
// with the api path.
define(["jquery"], function($) {
    "use strict";
    var config = function() {
        this.__rest_server_url = 'https://gizur.com/api/';

        // Messages
        // ========
        // Following are the set of messages showed to 
        // user on perticular response / error code.
        this.__messages = {
            "CLIENT_ID_INVALID": 'Client ID is not available.',
            "EMAIL_INVALID": 'Email is already registred.',
            "ERROR": 'An error occured, Please contact administrator.',
            "WRONG_CREDENTIALS": 'Credentials are in valid.',
            "WRONG_FROM_CLIENT": 'From client is incorrect.',
            "INVALID_EMAIL": "Wrong email id provided."
        };

        // Varibales to hold controllers object
        //
        this.user_controller = null;
        this.account_controller = null;
        this.clients_controller = null;

    };
    return config;
});