// TrinityWeb Javascript file.

var mouseY = 0;
var arrow = 0;

$(document).ready(function() {
	$('#popup').center();
	$("#overlay").width=window.width;
	$("#overlay").height=window.height;
	
	$(document).mousemove(function(e){
		mouseY = e.pageY;
  });
  
});

$(".login_input").focus(function() {
	
	if(this.value=="Username...") {
		this.value="";
	} else if(this.value="Password...") {
		this.value="";
	}
	
});

function vote(siteid,button) {
  $(button).attr("disabled", "true");
  $.post("includes/scripts/vote.php", { siteid: siteid },
               function(data) {
              window.location=data;
          });
	
}  

function changeAmount(input) {
	var amount = document.getElementById("amount");
	amount.value=input.value;
}

$(".content_hider").click(function() {
	$(this).toggleClass("content_hider_highlight");
	if($(this).next().is(":hidden")) {
			 $(this).next().slideDown("fast");
		} else {
          $(this).next().slideUp("fast");
		}
});

function buyShopItem(type,entry,last_page,account_name) {
	var character = document.getElementById("character");
	$("#overlay").fadeIn("fast");
	$("#shopContent").html("<b class='yellow_text'>Proccessing...</b>");
	 $.post("includes/scripts/sendReward.php", { item_entry: entry, character_realm: character.value, send_mode: type,last_page: last_page},
               function(data) {
				popUp("Item Purchased","The item was purchased and sent to your character via mail.");
               $("#shopContent").html(data);
			   $.post("includes/scripts/misc.php", { element: type, account: account_name},
                   function(data) {
                $("#" + type).html(data);
             });
          });

		   
}

function enableForumAcc() {
	$("#forumAcc").fadeIn();
}


function redirect(url) {
	$("#overlay").fadeIn("fast");
	window.location=url; 
}

function confirmItemDelete(url) {
var btn=confirm("Do you really wish to delete this item?");
if (btn==true) {
        redirect(url); 
  }
  else {
	 alert("Chicken!");
  }
}

(function($){
    $.fn.extend({
        center: function () {
            return this.each(function() {
                var top = ($(window).height() - $(this).outerHeight()) / 2;
                var left = ($(window).width() - $(this).outerWidth()) / 2;
                $(this).css({position:'absolute', margin:0, top: (top > 0 ? top : 0)+'px', left: (left > 0 ? left : 0)+'px'});
            });
        }
    }); 
})(jQuery);

function popUp(title,content) {
	$("#overlay").fadeIn("fast");
	$("#popup").fadeIn("slow");
	$("#popup_close").fadeIn();
	$("#popup_title").html(title + '<div id="popup_close" onclick="closePopup()"></div>');
	$("#popup_body").html("<span class='yellow_text'>" + content + "</span>\
	</p><hr/><center><input type='button' id='popup_close_button' value='Close' onclick='closePopup()'></center>");
	$("#popup").center();
}

function closePopup() {
	$("#overlay").fadeOut();
	$("#popup").fadeOut();
}

function register() {
	$("#overlay").fadeIn();
	$('#register').attr('disabled','disabled');

	var username = document.getElementById("username").value;
	var email = document.getElementById("email").value;
	var password = document.getElementById("password").value;
	var password_repeat = document.getElementById("password_repeat").value;	
	
	 $.post("includes/scripts/register.php", { register: "true", username: username,email: email,password: password,
	 password_repeat: password_repeat },
               function(data) {
				   if(data == "SUCCESS") {
					   popUp("Account Created","Your account has been created successfully, and you may now log in.");
				   } else {
				       popUp("Account Creation", data);
					   $('#register').removeAttr('disabled');
				   }
			   });
}

function checkUsername() {
   var username = document.getElementById("username").value;
   
   $("#username_check").fadeIn();
   $("#username_check").html("Checking for availability...");
   
    $.post("includes/scripts/register.php", { check: "username", value: username },
               function(data) {
				    $("#username_check").html(data);
			   });
}

 function acct_services(service) {
		  if(service=="character") {
				 $("#acct_services").html("\
				 <div class='service' onclick='redirect(\"?p=unstuck\")'><div class='service_icon'>\
				 <img src='styles/global/images/icons/race_change.png'></div><h3>Unstuck</h3>Unstuck your character.</div> \
				 <div class='service' onclick='redirect(\"?p=revive\")'><div class='service_icon'>\
				 <img src='styles/global/images/icons/revive.png'></div>\
				 <h3>Revive</h3>Revive your character.</div> \
				 <div class='service' onclick='redirect(\"?p=teleport\")'><div class='service_icon'>\
				 <img src='styles/global/images/icons/transfer.png'></div>\
				 <h3>Teleport</h3>Teleport your character.</div> \
				 ");
				 $("#acct_services").fadeIn(500);
			  }
			if(service=="account") {
                 $("#acct_services").html("\
				 <div class='service' onclick='redirect(\"?p=vote\")'><div class='service_icon'>\
				 <img src='styles/global/images/icons/character_migration.png'></div>\
				 <h3>Vote</h3>Vote & recieve rewards.</div> \
				 <div class='service' onclick='redirect(\"?p=donate\")'><div class='service_icon'>\
				 <img src='styles/global/images/icons/visa.png'></div>\
				 <h3>Donate</h3>Donate & recieve rewards.</div> \
				 <div class='service' onclick='redirect(\"?p=voteshop\")'><div class='service_icon'>\
				 <img src='styles/global/images/icons/raf.png'></div>\
				 <h3>Vote Shop</h3>Claim your rewards!</div> \
				 <div class='service' onclick='redirect(\"?p=donateshop\")'><div class='service_icon'>\
				 <img src='styles/global/images/icons/raf.png'></div>\
				 <h3>Donation Shop</h3>Claim your rewards!</div> \
				 ");
				 $("#acct_services").fadeIn(500);
			 }	
            if(service=="settings") {
                 $("#acct_services").html("\
				 <div class='service' onclick='redirect(\"?p=changepass\")'><div class='service_icon'>\
				 <img src='styles/global/images/icons/arena.png'></div>\
				 <h3>Change Password</h3>Change your password.</div> \
				 <div class='service' onclick='redirect(\"?p=settings\")'><div class='service_icon'>\
				 <img src='styles/global/images/icons/ptr.png'></div>\
				 <h3>Account Settings</h3>Change your personal settings.</div> \
				 ");
				 $("#acct_services").fadeIn(500);
             }			
		  }
function unstuck(guid,char_db) {
	popUp("Proccessing...");
	$.post("includes/scripts/character.php", { action: "unstuck", guid: guid, char_db: char_db},
               function(data) {
				popUp("Unstucked!","Your character was successfully unstucked!");
          });
}

function revive(guid,char_db) {
	popUp("Proccessing...");
	$.post("includes/scripts/character.php", { action: "revive", guid: guid, char_db: char_db},
               function(data) {
				popUp("Revived!","Your character was successfully revived!");
          });
}

function removeItemFromCart(cart,entry) {
	$.post("includes/scripts/shop.php", { action: "removeFromCart", cart: cart, entry:entry},
               function(data) {
			     window.location='?p=cart';
          });
}

function addCartItem(entry,cart,shop,button) {
	$(button).attr("disabled", "true");
	if(arrow==0) {
	$("#cartArrow").fadeIn(400);
	$("#cartArrow").css("top",mouseY-200 + "px");
	
	$("#cartArrow").animate({
        top: "35px"
    }, mouseY + 500 );
	 arrow=1;
	}
	
	$.post("includes/scripts/shop.php", { action: "addShopitem", cart: cart, entry:entry,shop: shop},
               function(data) {
				   loadMiniCart(cart);
				   $("#status-" + entry).fadeIn(200).delay(1100).fadeOut(200);
					setTimeout(function()
					{
					  $(button).removeAttr("disabled");
					  $("#cartArrow").fadeOut(400);
					}, 1350);
					 
          });
}

function clearCart() {
	$.post("includes/scripts/shop.php", { action: "clear"},
               function(data) {
                  window.location='?p=cart';
          });
}

function loadMiniCart(cart) {
	$.post("includes/scripts/shop.php", { action: "getMinicart",cart:cart},
               function(data) {
                  $("#cartHolder").html(data);
          });
}

function saveItemQuantityInCart(cart,entry) {
	
	var quantity = document.getElementById(cart + "Quantity-" + entry).value;
	
	$.post("includes/scripts/shop.php", { action: "saveQuantity", cart:cart, entry:entry, quantity: quantity},
               function(data) {
                  window.location='?p=cart'
          });
}

function checkout() {
	
	var values = document.getElementById("checkout_values").value;
	
	popUp("Proccessing...","Proccessing your payment & sending the items...");
	$.post("includes/scripts/shop.php", { action: "checkout", values:values},
               function(data) {
				   if(data==true) {
					 window.location='?p=cart&return=true'  
				   } else {
					 window.location='?p=cart&return=' + data;  
				   }
          });
		  
}

function viewTos() {

	$.post("includes/scripts/misc.php", { getTos: true},
               function(data) {
				popUp("Terms of Service",data);
          });

}

/* Teleportation system */
var selected_char = 0;
var box_char = 0;
function selectChar(values) { 
	 if (selected_char!=0) {
		  box_char = document.getElementById(selected_char); $(box_char).css("background-color","#181818");
	 }
	  selected_char = values; box_char = document.getElementById(selected_char);  $(box_char).css("background-color","#111");
	  
		  $("#teleport_to").fadeIn("slow"); 
		  $("#teleport_to").html("Loading...");
		   
		   
		  $.post("includes/scripts/character.php", { action: "getLocations", values: values},
		   function(data) {
				 $("#teleport_to").html(data);  
		   }); 
}

function portTo(locationTo,char_db,character) {
	popUp("Confirm Teleport","Are you sure you wish to teleport this character to " + locationTo + "?<br/><br/>\
	<input type='button' value='Yes I do' onclick='portNow(\""+ character +"\",\""+ locationTo +"\",\""+ char_db +"\")'> \
	<input type='button' value='No' onclick='closePopup()'>");
	
}

function portNow(character,location,char_db) {

	 $.post("includes/scripts/character.php", { action: "teleport", character: character, location: location,char_db: char_db},
		function(data) {
		   popUp("Character Teleport",data);
	}); 
}
	
