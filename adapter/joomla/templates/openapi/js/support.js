/*
 * @copyright Copyright © 2013, TIBCO Software Inc. All rights reserved.
 * @license GNU General Public License version 2; see LICENSE.txt
 */

(function($) {
	$("#support_area form button").live("click", function (){
		var textLength = $("#support_area").find("form textarea").val().trim().length,
		emailPattern = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/,
		email = $('input[name="email"]').val(),
		fname = $('input[name="fname"]').val(),
		lname = $('input[name="lname"]').val();
		fname = fname===$('input[name="fname"]')[0].defaultValue?'':fname;
		lname = lname===$('input[name="lname"]')[0].defaultValue?'':lname;
		
		if (fname==='') {
			$('input[name="fname"]').val('User');
		}
		
		if (!emailPattern.test(email)) {
			Joomla.showError([SUPPORT_INPUT_EMAIL]);
			return false;
		}
		
		if(textLength>0){
			Joomla.removeMessages();
			$("#support_area").find("form button.btn").attr("disabled",true);
			$("#support_area").find("div#msg_box").remove();
			$.post(
				GLOBAL_CONTEXT_PATH+"index.php?option=com_cobalt&task=ajaxMore.requestSupport", 
				$("#support_area form").serialize(),
				function(data){
					if (data.success) {
						$("#support_area").prepend("<div class='alert alert-success' id='msg_box'><h4 class='alert-heading'>Message</h4><p>Support email sent successfully!</p></div>");
						$("#support_area").find("form textarea").val("");
						$("#support_area").find("form input[type='text']").val();
					}else{
						$("#support_area").prepend("<div class='alert alert-error' id='msg_box'><h4 class='alert-heading'>Message</h4><p>"+obj.error+"</p></div>");
					}
					$("#support_area").find("form button.btn").attr("disabled",false);
				},
				'json'
			);
		} else{
			Joomla.showError([SUPPORT_INPUT_QUESTION]);
			return false;
		}

		return false;
	});

	$(document).ready(function(){
		$('input[name="fname"]').css("width","40%").css("margin-right","10px");
		$('input[name="lname"]').css("width","30%");
		$('input[name="email"]').css("width","74%");
		if (SUPPORT_USER_EMAIL.length>0) {
			$('input[name="email"]').val(SUPPORT_USER_EMAIL).hide();
			$('input[name="email"]').prev("p").hide();
			$('input[name="fname"]').val(SUPPORT_USER_NAME);
		}
		if (SUPPORT_USER_NAME.length>0) {
			$('#u_name_span').html(SUPPORT_USER_NAME);
			$('input[name="fname"]').hide();
			$('input[name="lname"]').hide();
			$('input[name="fname"]').prev("p").hide();
		}		
		
		$('input[type="text"]').each(function(index, item) {
		    $(item).blur(function(){
				if ($(this).val().trim()==='') {
					$(this).val(this.defaultValue);
				}
		    });
		
		    $(item).focus(function(){
				if ($(this).val()===this.defaultValue) {
					$(this).val('');
				}
		    });
		});

        // parse the parameters in the URL to decide whether to pre-populate the query field or not
        var sSearch = window.location.search,
            aParameters = sSearch ? sSearch.substring(1).split('&') : [],
            bPrepopulate = false,
            sUUID, i, aKV, sK, sV,
            fPrepopulate = function(sUUID) {
                var dQuery = $('textarea[name="content"]');
                dQuery.val(SUPPORT_PREPOPULATED_TEXT_UUID.replace(/<br\s*\/>/g, '\n\n').replace(/\{uuid\}/, sUUID));
            };
        for(i = 0; i < aParameters.length; i++) {
            aKV = aParameters[i].split('=');
            sK = aKV[0], sV = aKV[1];
            if(sK === 'prepopulate' && sV === '1') {
                bPrepopulate = true;
            } else if(sK === 'uuid') {
                sUUID = sV;
            }
        }

        if(bPrepopulate && sUUID) {
            fPrepopulate(sUUID);
        }

	});
}(jQuery));