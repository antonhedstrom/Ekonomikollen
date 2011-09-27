<?php
function DatePartFromDate($ReturnFormat,$InputDate)
{
	return date($ReturnFormat,strtotime($InputDate));
}
?>