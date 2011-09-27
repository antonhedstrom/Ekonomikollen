

$(document).ready(function(){  
  
   // Fade in start page
	$('#head').fadeIn(700);
	
	$('#add_toggle').click(
		function() {
			$('#add_form').slideToggle();
		}
	);
	
	$('#archive_toggle').click(
		function() {
			$('#archive_display').slideToggle();
		}
	);
 
	//Datepicker
	$("#add_date").datepicker({ dateFormat: 'ymmdd'});
	$("#add_date").datepicker( "option", "firstDay", 1 );


});
