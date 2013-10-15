<?php
$tpl_element = array();

ob_start(); ?>
<h2><?php _e('Home consumption dashboard', 'wp_home_mngt'); ?></h2>
<div id="wphmngt-dashboard-widgets" class="metabox-holder columns-2">
	{WPHMNGT_TPL_DASHBOARD}
</div>
<!-- wphmngt-dashboard-widgets-wrap --><?php
$tpl_element[ 'dashboard_layout' ][ 'tpl' ] = ob_get_contents();
ob_end_clean();