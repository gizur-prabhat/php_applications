'use strict';

var AccountsController = Stapes.subclass({
    constructor : function() {
        var self = this;
        //Form
        self.$el = $("#apisettingform");
        var $client_id = this.$el.find("#client_id");
        var $api_key_1 = this.$el.find("#api_key_1");
        var $api_key_2 = this.$el.find("#api_key_2");
        var $secret_key_1 = $("#secret_key_1");
        var $secret_key_2 = $("#secret_key_2");

        self.model = new AccountModel($client_id.val(), $api_key_1.val(), 
            $api_key_2.val(), $secret_key_1.text(), $secret_key_2.text());
        self.view = new AccountsView( this.model );
        
        self.$el.on('submit', function(e) {
            e.preventDefault();
        });
        
        self.model.on('generateAPIKeyAndSecret1', function() {
            console.log('generateAPIKeyAndSecret1');
            
            var _url = __rest_server_url + 'User/keypair1/' + encodeURIComponent(__client_email);
    
            $.ajax({
                url: _url,
                type: "PUT",
                dataType: "json",
                error: function() {
                    $('#errorMessageBox').removeClass('alert-success')
                    $('#errorMessageBox').addClass('alert alert-error')
                    .empty()
                    .html('<button data-dismiss="alert" class="close" type="button">×</button>An error occured while re-generating the key pair. Please try again.');
                },
                success : function(_data){
                    if(_data.success){
                        $('#errorMessageBox').removeClass('alert-error')
                        $('#errorMessageBox').addClass('alert alert-success')
                        .empty()
                        .html('<button data-dismiss="alert" class="close" type="button">×</button>Key pair has been generated successfully.');
                        
                        //Set modified values to the Account Object
                        self.model.set({'api_key_1':_data.result.apikey_1,'secret_key_1':_data.result.secretkey_1});
                        //Map values to the page
                        self.model.mapValues();
                        $('#generateNewAPIAndSecretKey1Close').click();
                    }else{
                        $('#errorMessageBox').removeClass('alert-success')
                        $('#errorMessageBox').addClass('alert alert-error')
                        .empty()
                        .html('<button data-dismiss="alert" class="close" type="button">×</button>An error occured while re-generating the key pair. Please try again.');
                    }
                }
            });
        });
        
        self.model.on('generateAPIKeyAndSecret2', function() {
            console.log('generateAPIKeyAndSecret2');
            
            var _url = __rest_server_url + 'User/keypair2/' + encodeURIComponent(__client_email);
    
            $.ajax({
                url: _url,
                type: "PUT",
                dataType: "json",
                error: function() {
                    $('#errorMessageBox').removeClass('alert-success')
                    $('#errorMessageBox').addClass('alert alert-error')
                    .empty()
                    .html('<button data-dismiss="alert" class="close" type="button">×</button>An error occured while re-generating the key pair. Please try again.');
                },
                success : function(_data){
                    if(_data.success){
                        $('#errorMessageBox').removeClass('alert-error')
                        $('#errorMessageBox').addClass('alert alert-success')
                        .empty()
                        .html('<button data-dismiss="alert" class="close" type="button">×</button>Key pair has been generated successfully.');
                        
                        //Set modified values to the Account Object
                        self.model.set({'api_key_2':_data.result.apikey_2,'secret_key_2':_data.result.secretkey_2});
                        //Map values to the page
                        self.model.mapValues();
                        $('#generateNewAPIAndSecretKey2Close').click();
                    }else{
                        $('#errorMessageBox').removeClass('alert-success')
                        $('#errorMessageBox').addClass('alert alert-error')
                        .empty()
                        .html('<button data-dismiss="alert" class="close" type="button">×</button>An error occured while re-generating the key pair. Please try again.');
                    }
                }
            });
        });
    }
});