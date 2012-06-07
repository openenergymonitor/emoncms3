<?php

include "Models/dashboards_model.php";

function render_dashboard($userid)
{
	return build_dashboardmenu($userid);
}

function build_dashboardmenu($userid)
{
	$dsb = get_dashboards_info($userid);	
	
	$k = sizeof($dsb);
	
	$topmenu = '<div class="e3menu"><div class="e3header"><ul id="e3top-menu">';
			
	while ($k>0) {
		$row = $dsb[$k-1];
		$k = $k - 1;
		
		//if ($row['main'] == TRUE) 
		$content = $row['content'];
				
		$topmenu = $topmenu.'<li><a href="">'.$row['name'].'</a></li>';
	
	}
	
	$topmenu = $topmenu.'</ul></div></div>'; 
	
	return $topmenu.$content;	
}

?>