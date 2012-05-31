<?php

include "Models/dashboards_model.php";

function render_dashboard($userid)
{
	$dsb = get_dashboards_info($userid);	
	
	$k = sizeof($dsb);
	
	$topmenu = "";
	
	while ($k>0) {
		$row = $dsb[$k-1];
		$k = $k - 1;
		
		if ($row['main'] == TRUE) $content = $row['content'];
				
		$topmenu = $topmenu.$row['name'];
	
	}
	
	return $topmenu.$content;	
}

?>