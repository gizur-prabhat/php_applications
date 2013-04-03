
/**
 * This file contains routing function used throughout Gizur SaaS.
 *
 * @package    Gizur SaaS
 * @subpackage 
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

"use strict";

// Instanciate the controllers
//
var user_controller = null;
var account_controller = null;

$(function() {
    // DEFAULT HASH
    // ============
    // 
    // This is a default hash.
    var DEFAULT_HASH = 'registration';
    
    // Prepend Hash
    // =============
    // 
    // This tells the haser to prepend the chars specified
    // with all query string after hash.
    hasher.prependHash = "!";
    
    // Crossroads SetUp
    // ================
    // 
    // Adds routers to the application.
    
    // Route1 : Registration Process
    // =============================
    //
    // This is a default router which excute in case of match
    // the route1 function defined below.    
    var route1 = crossroads.addRoute('registration');
    
    // Route2 : User Details
    // =====================
    //
    // Excutes the route2 function defined below.
    // Here client_id and session_id are not fixed.
    var route2 = crossroads.addRoute('user/{client_id}/{session_id}');
    
    // Route3 : Login Process
    // ======================
    //
    // Excutes the route3 function defined below.
    // Here status is optional parameter but it can be
    // empty, fail or success.
    var route3 = crossroads.addRoute('login/:status:');
    
    // Route4 : Logout Process
    // ======================
    //
    // Excutes the route4 function defined below
    // to logout the user.
    var route4 = crossroads.addRoute('logout');
    
    // Log All Routes
    // ==============
    // 
    // To log all routes un-comment the following line
    //crossroads.routed.add(console.log, console);
    
    // Route1
    // ======
    // 
    // If route1 matched, the following function
    // gets executed.
    route1.matched.add(function(){
        user_controller = new UsersController();
    });
    
    // Route2
    // ======
    // 
    // If route2 matched, the following function
    // gets executed.
    route2.matched.add(function(client_id, session_id){     
        account_controller = new AccountsController(DEFAULT_HASH, client_id);
    });
    
    // Route3
    // ======
    // 
    // If route1 matched, the following function
    // gets executed.
    route3.matched.add(function(status){
        user_controller.login(status);
    });
    
    // Route4
    // ======
    // 
    // If route1 matched, the following function
    // gets executed.
    route4.matched.add(function(){
        hasher.setHash(DEFAULT_HASH);
    });
    
    // Default Hash
    // ============
    // 
    // Set a default hash value
    if(! hasher.getHash()){
        hasher.setHash(DEFAULT_HASH);
    }

    // ParseHash
    // =========
    // 
    // Used to parsh hash. If new hash found it'll
    // call the crossroad to handle.
    function parseHash(newHash, oldHash){
        // second parameter of crossroads.parse() is 
        // the "defaultArguments" and should be an array
        // so we ignore the "oldHash" argument to avoid issues.
        crossroads.parse(newHash);
    }

    // Initialize Haser
    // =================
    // 
    // parse initial hash
    hasher.initialized.add(parseHash);
    
    // parse hash changes
    hasher.changed.add(parseHash);
    
    // start listening for hash changes
    hasher.init();
});
