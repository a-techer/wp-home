<?php
$tpl_element = array();

/**	Dashboard main template	*/
ob_start(); ?><div class="wphmngt-dashboard-header" ><div class="icon32 icon32-posts-wphmngt_home_dashboard" id="icon-edit"><br /></div><h2><?php _e( 'Dashboard', 'wp_home_mngt' ); ?></h2></div><div class="wphmngt-alert wphmngt-dashboard-alert" ></div><div class="clear" ></div>
<div id="wphmngt-dashboard-account-container" >{WPHMNGT_TPL_ACCOUNT_LIST}<span class="wphmngt-account-creation-button" ><a title="<?php _e('Add new account', 'wp_home_mngt'); ?>" href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=ajax-wphmngt-new-account-form-load&amp;width=250&amp;height=160" class="thickbox" ><img src="<?php echo WP_HOME_MNGT_COMMON_TPL_URL; ?>medias/add_22.png" alt="<?php _e('Add new account', 'wp_home_mngt'); ?>" title="<?php _e('Add new account', 'wp_home_mngt'); ?>" /></a></span>
</div><!-- wphmngt-dashboard-account-list -->
<div id="wphmngt-dashboard-widgets" class="metabox-holder columns-2">{WPHMNGT_TPL_DASHBOARD}</div>
<!-- wphmngt-dashboard-widgets-wrap --><?php
$tpl_element[ 'dashboard_layout' ][ 'tpl' ] = ob_get_contents();
ob_end_clean();

/**	Dashboard account list template	*/
ob_start(); ?><img src="<?php echo WP_HOME_MNGT_COMMON_TPL_URL; ?>medias/create_first_account.png" alt="<?php _e( 'No account founded', 'wp_home_mngt' ); ?>" class="wphmngt_no_existing_account" /><?php
$tpl_element[ 'dashboard_account_empty_list' ][ 'tpl' ] = ob_get_contents();
ob_end_clean();

/**	Dashboard account list template	*/
ob_start(); ?><ul class="wphmngt-dashboard-account-list" >{WPHMNGT_TPL_ACCOUNT_LIST}</ul><?php
$tpl_element[ 'dashboard_account_list_container' ][ 'tpl' ] = ob_get_contents();
ob_end_clean();

/**	Dashboard account item template	*/
ob_start(); ?><li id="wphmngt-account-{WPHMNGT_TPL_ACCOUNT_ID}" >
	<h3 class="wphmngt-account-name" >{WPHMNGT_TPL_ACCOUNT_NAME}</h3>
	<ul class="wphmngt-account-details" >
		<li class="wphmngt-account-current-balance" >{WPHMNGT_TPL_ACCOUNT_INFOS_CURRENT_BALANCE}</li>
	</ul><!-- wphmngt-account-details -->
	<a title="<?php _e('Edit account', 'wp_home_mngt'); ?>" href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=ajax-wphmngt-edit-account-form-load&amp;width=250&amp;height=160&account_ID={WPHMNGT_TPL_ACCOUNT_ID}" class="thickbox wphmngt-account-edition-button" ></a>
	<span class="wphmngt-account-deletion-button" ></span>
</li><?php
$tpl_element[ 'dashboard_account_item' ][ 'tpl' ] = ob_get_contents();
ob_end_clean();

/**	Dashboard account creation form template	*/
ob_start(); ?><div class="wphmngt-alert wphmngt-hide" id="wphmngt-account-form-message" ></div>
<div class="wphmngt-alert wphmngt-alert-error wphmngt-hide" id="wphmngt-account-form-uncomplete" ><?php _e( 'Please fill all fields with red marks', 'wp_home_mngt' ); ?></div>
<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="post" id="wphmngt-account-form" >
	<input type="hidden" name="action" value="ajax-wphmngt-save-account" />
	{WPHMNGT_TPL_CREATE_ACCOUNT_NONCE}
	<ul class="wphmngt-account-creation-form-container" >
		<li class="wphmngt-form-fields required" title="<?php _e( 'Required field', 'wp_home_mngt' ); ?>" >
			<label for="wphmngt_account_name" ><?php _e( 'Account name', 'wp_home_mngt' ); ?></label>
			<input type="text" id="wphmngt_account_name" name="wphmngt_account[posts][post_title]" value="{WPHMNGT_TPL_ACCOUNT_NAME}" />
		</li>
		<li class="wphmngt-form-fields required" title="<?php _e( 'Required field', 'wp_home_mngt' ); ?>" >
			<label for="wphmngt_account_current_balance" ><?php _e( 'Current balance', 'wp_home_mngt' ); ?></label>
			<input type="text" id="wphmngt_account_current_balance" name="wphmngt_account[postmeta][current_balance]" value="{WPHMNGT_TPL_ACCOUNT_CURRENT_BALANCE}" />
		</li>
		<li class="wphmngt-form-fields" >
			<label for="wphmngt_account_type" ><?php _e( 'Account type', 'wp_home_mngt' ); ?></label>
			{WPHMNGT_TPL_ACCOUNT_TYPE_LIST}
		</li>
		<li class="wphmngt-form-fields" >
			<label for="wphmngt_account_serial_number" ><?php _e( 'Account number', 'wp_home_mngt' ); ?></label>
			<input type="text" id="wphmngt_account_serial_number" name="wphmngt_account[postmeta][serial_number]" value="{WPHMNGT_TPL_ACCOUNT_SERIAL_NUMBER}" />
		</li>
		<li>
			{WPHMNGT_TPL_SAVE_ACCOUNT_BUTTON}
			<img class="alignright wphmngt-hide wphmngt-loading-picture" src="<?php echo admin_url( 'images/loading.gif' ); ?>" alt="<?php _e( 'loading in progress', 'wp_home_mngt' ); ?>" />
		</li>
	</ul><!-- wphmngt-account-creation-form-container -->
</form>
<script type="text/javascript" >
	wphmngt( document ).ready( function(){
		jQuery("#wphmngt_account_current_balance" ).numeric();
	});
</script><?php
$tpl_element[ 'account_form' ][ 'tpl' ] = ob_get_contents();
ob_end_clean();

/**	Account form save button - creation	*/
ob_start(); ?><button class="button-primary alignright" id="wphmngt-new-account-button" ><?php _e( 'Add account', 'wp_home_mngt' ); ?></button><?php
$tpl_element[ 'account_save_button_creation' ][ 'tpl' ] = ob_get_contents();
ob_end_clean();

/**	Account form save button - edition	*/
ob_start(); ?><button class="button-primary alignright" id="wphmngt-edit-account-button" ><?php _e( 'Save account', 'wp_home_mngt' ); ?></button><input type="hidden" name="wphmngt_account[posts][ID]" value="{WPHMNGT_TPL_ACCOUNT_ID}" /><?php
$tpl_element[ 'account_save_button_edition' ][ 'tpl' ] = ob_get_contents();
ob_end_clean();

?>