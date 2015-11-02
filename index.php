<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href='amit_button.css'/>
<title>TSP : PHP Implementation</title>
<style>
body {
	text-align: center;
	margin: 0px; padding: 0px;
}
body, td, th, input {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
}
h1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 15px;
	text-align: center;
}
.cityCell {
	width: 60px;
}
.input {
	background-color: #CCFFFF;
	border: 1px solid #ccc;
	padding: 1px;
	margin: 1px;
}
#container {
	margin: 0 auto 0 auto; padding: 10px;
	width: 400px;
	text-align: left;
	border-left: 3px dotted #333;
	border-right: 3px dotted #333;
	border-bottom: 3px dotted #333;
}
form {
	margin: 0px; padding: 0px;
}
.debug td {
	padding: 0 2px 0 2px;
}
</style>
<script>
function tsp()
{
	var no = document.getElementById("city").value;
	if(!no || isNaN(no))
	{
		alert("Correct your inputs");
		return false;
	}
	window.location.href = "process.php?no_of_cities="+no;
}
</script>
</head>

<body><div id="container">
<h1>TSP : PHP Implementation by Amit Kumar Mondal</h1><div align='center'><h4>Please input the no of cities</h4></div>
<div align='center'><input type='text' class='input' id='city' autocomplete='off' name='city'/></div>
<div align='center'><input type='button' class='amit_button' value='Process' onclick='tsp()'/></div>
</div>
