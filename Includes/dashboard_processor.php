<?php

include "Models/dashboards_model.php";

function render_dashboard($userid)
{
	// Get possible dashboard menu
	$menu = build_dashboardmenu($userid);
	
	$id = $_GET['id'];
	
	if ($id) {
		$ds = get_dashboard_id($userid, $id);
		
		return $menu.$ds['ds_content'];	  
	}
	else {
		$ds = get_dashboard($userid);
		
		return $menu.$ds['ds_content'];
	}
}

function build_dashboardmenu($userid)
{
	$dsb = get_dashboards_info($userid);	
	
	$k = sizeof($dsb);
	
	// Only show menu if more than one dashboard were created
	if ($k>1)
	{
		$topmenu = '<div class="e3menu"><div class="e3header"><ul id="e3top-menu">';
			
		while ($k>0) {
			$row = $dsb[$k-1];
			$k = $k - 1;
				
			$topmenu = $topmenu.'<li><a href="./run&id='.$row['id'].'">'.$path.$row['name'].'</a></li>';
		}
	
		$topmenu = $topmenu.'</ul></div></div>'; 
	}
	return $topmenu;	
}

?>