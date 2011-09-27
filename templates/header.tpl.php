<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>

	<title>Budget</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" type="text/css" href="layout/position.css">
	<link rel="stylesheet" type="text/css" href="layout/style.css">
	<link rel="stylesheet" type="text/css" href="layout/jquery-ui-1.8.11.custom.css">
	
	<script type="text/javascript" src="includes/jscript/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="includes/jscript/jquery-ui-1.8.11.custom.min.js"></script>
	<script type="text/javascript" src="includes/jscript/jquery-mycalls.js"></script>
	<script type="text/javascript" src="includes/jscript/sorttable.js"></script>
	
	<script type="text/javascript">
		function setFocus()
		{
			document.getElementById("focus").focus();
		}
	</script>
	
	
	<if name="individual_color">
    <!-- Some individual override stuff -->
    <style type="text/css">
      .sortable thead th, #archive a, .stats a, tr.current{ 
        color: #{color};
      }
      #icons img:hover, #add_toggle:hover, #archive_toggle:hover {
        background-color: #{color};
      }
    </style>
	</if name="individual_color">
	
</head>
<body onload="setFocus();">

<div id="head">