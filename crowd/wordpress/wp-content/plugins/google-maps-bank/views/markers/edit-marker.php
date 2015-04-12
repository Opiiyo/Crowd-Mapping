<?php
if (!is_user_logged_in())
{
	return;
}
else
{
	switch($gmb_role)
	{
		case "administrator":
			$user_role_permission = "manage_options";
		break;
		case "editor":
			$user_role_permission = "publish_pages";
		break;
		case "author":
			$user_role_permission = "publish_posts";
		break;
	}
	if (!current_user_can($user_role_permission))
	{
		return;
	}
	else
	{
		$add_new_marker = wp_create_nonce("new_marker_add");
		$update_marker = wp_create_nonce("marker_update");
		
		if(isset($_REQUEST["map_id"]))
		{
			if(file_exists(MAP_BK_PLUGIN_DIR ."/lib/get-map-settings.php"))
			{
				include_once MAP_BK_PLUGIN_DIR ."/lib/get-map-settings.php";
			}
			$map_marker_count = $wpdb->get_var
			(
				$wpdb->prepare
				(
					"SELECT count(id) FROM ".map_bank_create_new_map_table()." where  parent_id = %d AND map_type = %s ",
					$map_id,
					"marker"
				)
			);
		}
		?>
		<form id="frm_edit_marker" class="layout-form" style="max-width:1000px;">
			<div class="fluid-layout">
				<div class="layout-span12 responsive">
					<div class="widget-layout">
						<div class="widget-layout-title">
							<h4>
								<?php _e("Edit Marker", map_bank); ?>
							</h4>
						</div>
						<div class="widget-layout-body">
							<div class="fluid-layout">
								<input type="button" onclick="proceed_to_back();" id="ux_btn_action" name="ux_btn_action" class="btn btn-danger"value="<?php _e("Back to Manage Maps", map_bank); ?>"/>
								<?php	
									if(isset($_REQUEST["mid"]))
									{
										?>
										<input type="submit" id="ux_btn_add_marker" value="<?php _e("Update Marker", map_bank); ?>" name="ux_btn_add_marker" class="btn btn-danger" style="float:right;margin-left: 5px;margin-top: 10px;"/>
										<?php
									}
									else 
									{
										if($map_marker_count < 5)
										{
											?>
											<input type="submit" id="ux_btn_add_marker" value="<?php _e("Add Marker", map_bank); ?>" name="ux_btn_add_marker" class="btn btn-danger" style="float:right;margin-left: 5px;margin-top: 10px;"/>
											<?php
										}
									}
								?>
							</div>
							<div class="separator-doubled" style="margin-bottom: 20px;"></div> 
							<div class="fluid-layout">
								<div class="layout-span12 responsive">
									<div class="layout-control-group">
										<label class="layout-control-label-location layout-control-label"><?php _e("Formatted Address", map_bank); ?> :
											<span class="hovertip" data-original-title ="<?php _e("Formatted Address are used for points of interest and geocodes as below, plus a slight variant is used for streets.",map_bank) ;?>">
												<img class="tooltip_img" src="<?php echo MAP_BK_TOOLTIP?>"/>
											</span>
										</label>
										<div class="layout-controls-location custom-layout-controls-map-location">
											<input type="text" id="geocomplete_marker" name="geocomplete_marker" class="layout-span12" value="<?php echo stripcslashes(htmlspecialchars_decode($map_location_title));?>" placeholder="<?php _e("Enter the Location Title here", map_bank); ?>"/>
										</div>
									</div>
								</div>
							</div>
							<div class="fluid-layout">
								<div class="layout-span6 responsive" style="margin-bottom:10px;">
									<div class="layout-control-group">
										<label class="layout-control-label-location layout-control-label"><?php _e("Marker Name", map_bank); ?> :
											<span class="error">*</span>
											<span class="hovertip" data-original-title ="<?php _e(" Here you to specify name to your marker to distinguish it separately.",map_bank) ;?>">
												<img class="tooltip_img" src="<?php echo MAP_BK_TOOLTIP?>"/>
											</span>
										</label>
										<div class="layout-controls-location custom-layout-controls-map-location">
											<input type="text" id="ux_txt_marker_name" class="layout-span12" name="ux_txt_marker_name" value="<?php echo $map_marker_name_update;?>" placeholder="<?php _e("Add Name to Marker", map_bank); ?>"/>
										</div>
									</div>
								</div>
								<div class="layout-span6 responsive" style="margin-bottom:10px;">
									<div class="layout-control-group">
										<label class="layout-control-label-location layout-control-label"><?php _e("Category", map_bank); ?> :
											<span class="error">*</span>
											<span class="hovertip" data-original-title ="<?php _e(" Here you to specify name to your marker to distinguish it separately.",map_bank) ;?>">
												<img class="tooltip_img" src="<?php echo MAP_BK_TOOLTIP?>"/>
											</span>
										</label>
										<div class="layout-controls-location custom-layout-controls-map-location">
											<select id="ux_ddl_Marker" name="ux_ddl_Marker" class="layout-span12" onchange="marker_change_catgory();">
												<option value="0"><?php _e("Select Marker Category", map_bank); ?></option>
												<optgroup label="Culture & Entertainment">
													<option value="1"><?php _e("Culture", map_bank); ?></option>
													<option value="2"><?php _e("Entertainment", map_bank); ?></option>
												</optgroup>
												<optgroup label="Events">
													<option value="3"><?php _e("Crime", map_bank); ?></option>
													<option value="4"><?php _e("Natural Disasters", map_bank); ?></option>
												</optgroup>
												<optgroup label="Health And Education">
													<option value="5"><?php _e("Education", map_bank); ?></option>
													<option value="6"><?php _e("Health", map_bank); ?></option>
												</optgroup>
												<optgroup label="Industry">
													<option value="7"><?php _e("Electric Power", map_bank); ?></option>
													<option value="8"><?php _e("Military", map_bank); ?></option>
												</optgroup>
												<optgroup label="Miscellaneous">
													<option value="9"><?php _e("Miscellaneous", map_bank); ?></option>
													<option value="10"><?php _e("Media", map_bank); ?></option>
													<option value="11"><?php _e("Days", map_bank); ?></option>
													<option value="12"><?php _e("Numbers", map_bank); ?></option>
													<option value="13"><?php _e("Letters", map_bank); ?></option>
													<option value="14"><?php _e("Special Characters", map_bank); ?></option>
												</optgroup>
												<optgroup label="Nature">
													<option value="15"><?php _e("Agriculture", map_bank); ?></option>
													<option value="16"><?php _e("Animals", map_bank); ?></option>
													<option value="17"><?php _e("Natural Marvels", map_bank); ?></option>
													<option value="18"><?php _e("Weather", map_bank); ?></option>
												</optgroup>
												<optgroup label="Offices">
													<option value="19"><?php _e("City Services", map_bank); ?></option>
													<option value="20"><?php _e("Interior", map_bank); ?></option>
													<option value="21"><?php _e("Real Estate", map_bank); ?></option>
												</optgroup>
												<optgroup label="People">
													<option value="22"><?php _e("Kids", map_bank); ?></option>
													<option value="23"><?php _e("People", map_bank); ?></option>
													<option value="24"><?php _e("Home", map_bank); ?></option>
												</optgroup>
												<optgroup label="Restaurants & Hotels">
													<option value="25"><?php _e("Bars", map_bank); ?></option>
													<option value="26"><?php _e("Hotels", map_bank); ?></option>
													<option value="27"><?php _e("Restaurants", map_bank); ?></option>
													<option value="28"><?php _e("Takeaway", map_bank); ?></option>
												</optgroup>
												<optgroup label="Sports">
													<option value="29"><?php _e("Sports", map_bank); ?></option>
												</optgroup>
												<optgroup label="Stores">
													<option value="30"><?php _e("Apparel", map_bank); ?></option>
													<option value="31"><?php _e("Brands Chains", map_bank); ?></option>
													<option value="32"><?php _e("Computer Electronics", map_bank); ?></option>
													<option value="33"><?php _e("Food Drinks", map_bank); ?></option>
													<option value="34"><?php _e("General Merchandise", map_bank); ?></option>
												</optgroup>
												<optgroup label="Transportation">
													<option value="35"><?php _e("Aerial Transportation", map_bank); ?></option>
													<option value="36"><?php _e("Directions", map_bank); ?></option>
													<option value="37"><?php _e("Other Transportation", map_bank); ?></option>
													<option value="38"><?php _e("Road Signs", map_bank); ?></option>
													<option value="39"><?php _e("Road Transportation", map_bank); ?></option>
												</optgroup>
												<optgroup label="Tourism">
													<option value="40"><?php _e("Religion", map_bank); ?></option>
													<option value="41"><?php _e("Tourism", map_bank); ?></option>
												</optgroup>
											</select>
										</div>
									</div>
								</div>
								<div class="layout-control-group">
									<div id="image_show" style="display:none;">
										<label class="layout-control-label-location layout-control-label"><?php _e("Choose Marker", map_bank); ?> : <span class="error">*</span>
											<span class="hovertip" data-original-title ="<?php _e("This option allows you to select the marker from the group of markers as per your requirement once the category is selected.",map_bank) ;?>">
												<img class="tooltip_img" src="<?php echo MAP_BK_TOOLTIP?>"/>
											</span>
										</label>
									</div>
									<div class="layout-controls-location custom-layout-controls-map-location" id="show_map_icons" style="display:none;padding:5px;">
									<?php 
									if(file_exists(MAP_BK_PLUGIN_DIR ."/includes/map-icons.php"))
									{
										include_once MAP_BK_PLUGIN_DIR . "/includes/map-icons.php";
									}	
									?>
									</div>
								</div>
							</div>
							<div class="fluid-layout" id="upload_img_div" style="display:none;">
								<input type="hidden" id="location_image_info_window" name="location_image_info_window" value="<?php echo esc_url($map_info_window_image_url_update);?>"/>
								<input type="hidden" id="ux_txt_latitude" class="layout-span12" name="ux_txt_latitude" value="<?php echo $map_location_latitude;?>"/>
								<input type="hidden" id="ux_txt_longitude" class="layout-span12" name="ux_txt_longitude" value="<?php echo $map_location_longitude;?>"/>
							</div>
							<div class="layout-control-group" id="container" style="display: none;">
								<label class="pricing-control-label"><?php _e("Custom Marker", map_bank); ?> :</label>
								<div class="pricing-layout-controls">
									<input type="button" id="pickfiles" class="btn btn-success" value="Choose Image">
									<div id="filelist"></div>
									<br>
									<span  class="span-custom"><?php _e("You can select Custom Marker from here", map_bank); ?></span>
								</div>
							</div>
							<div class="fluid-layout">
								<div class="layout-span12 responsive">
									<div class="layout-control-group">
										<label class="layout-control-label-location layout-control-label"><?php _e("Animation", map_bank); ?> :
											<span class="error">*</span>
											<span class="hovertip" data-original-title ="<?php _e("Animation is the process of creating motion and shape change illusion by means of the rapid display of a sequence of static images that minimally differ from each other",map_bank) ;?>">
												<img class="tooltip_img" src="<?php echo MAP_BK_TOOLTIP?>"/>
											</span>
										</label>
										<div class="layout-controls-location custom-layout-controls-map-location rdl_maps">
											<input type="radio" disabled="disabled" <?php echo ($map_marker_animation_update == 0) ? "checked=\"checked\"" : "";?> id="ux_rdl_animation_on"  name="ux_rdl_animation" value="0"/><?php _e("Drop", map_bank); ?>
											<input type="radio" disabled="disabled" <?php echo ($map_marker_animation_update == 1) ? "checked=\"checked\"" : "";?> id="ux_rdl_animation_off"  name="ux_rdl_animation" value="1"/><?php _e("Bounce", map_bank); ?>
											<i class="widget_premium_feature"><?php _e(" (Available in Premium Editions)", map_bank); ?></i><br>
										</div>
									</div>
								</div>
							</div>
							<div class="fluid-layout">
								<div class="layout-span12 responsive">
									<div class="layout-control-group">
										<label class="layout-control-label-location layout-control-label"><?php _e("Info-Window", map_bank); ?> :
										<span class="hovertip" data-original-title ="<?php _e("An Info window displays text or images in a popup window above the map. It allows to display information to the user when they tap on a marker.",map_bank) ;?>">
											<img class="tooltip_img" src="<?php echo MAP_BK_TOOLTIP?>"/>
										</span>
										</label>
										<div class="layout-controls-location custom-layout-controls-map-location rdl_maps">
											<input type="radio" disabled="disabled" <?php echo ($map_info_window_update == 1) ? "checked=\"checked\"" : "";?> id="ux_rdl_info_window_on" onclick="show_link_info_window();" name="ux_rdl_info_window" value="1"/><?php _e("Enable", map_bank); ?>
											<input type="radio" disabled="disabled" <?php echo ($map_info_window_update == 0) ? "checked=\"checked\"" : "";?> id="ux_rdl_info_window_off" onclick="show_link_info_window();" name="ux_rdl_info_window" value="0"/><?php _e("Disable", map_bank); ?>
											<i class="widget_premium_feature"><?php _e(" (Available in Premium Editions)", map_bank); ?></i><br>
										</div>
									</div>
								</div>
							</div>
							<div class="fluid-layout">
								<input type="hidden" id="marker_icon_image" name="marker_icon_image" value="<?php echo $map_marker_icon_update;?>"/>
								<div id="bind_map_location" style="max-height: 370px;">
									<div id="map_canvas" style="width: 100%; height: 345px; border:4px solid #e0dede; margin-top:10px;"></div>
								</div>
								<input type="button" onclick="proceed_to_back();" id="ux_btn_action" style="margin-top: 10px;" name="ux_btn_action" class="btn btn-danger"value="<?php _e("Back to Manage Maps", map_bank); ?>"/>
								<?php	
									if(isset($_REQUEST["mid"]))
									{
										?>
										<input type="submit" id="ux_btn_add_marker" value="<?php _e("Update Marker", map_bank); ?>" name="ux_btn_add_marker" class="btn btn-danger" style="float:right;margin-left: 5px;margin-top: 10px;"/>
										<?php
									}
									else
									{
										if($map_marker_count < 5)
										{
											?>
											<input type="submit" id="ux_btn_add_marker" value="<?php _e("Add Marker", map_bank); ?>" name="ux_btn_add_marker" class="btn btn-danger" style="float:right;margin-left: 5px;margin-top: 10px;"/>
											<?php
										}
									}
								?>
								<input type="button" id="ux_btn_clear_marker" onclick="clearMarkers();" name="ux_btn_clear_marker" class="btn btn-danger" value="<?php _e("Clear Marker", map_bank); ?>" style="float:right;display: none; margin-top: 10px;"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<script type="text/javascript">
		
			var url= "<?php echo plugins_url("/assets/",dirname(__FILE__));?>";
			var rand_num = Math.floor(Math.random()*10000);
			var marker_count = 0;
		
			var geocoder;
			var map;
			var marker;
			var latlng = "";
			var latlng1 = "";
			
			jQuery(document).ready(function()
			{
				initialize();
				jQuery(".hovertip").tooltip({placement: "right"});
				marker_change_catgory();
				jQuery("#manage_map").addClass("nav-tab-active");
				<?php 
					if(isset($map_info_window_image_url_update))
					{
						?>
						jQuery("#show_image").css("display","block");
						<?php
					}
					else {
						?>
						jQuery("#show_image").css("display","none");
						<?php
					}
				?>
				jQuery("#ux_ddl_Marker").val("<?php echo isset($map_marker_category_update) ? $map_marker_category_update : "1";?>");
			});
			
			jQuery("#frm_edit_marker").validate
			({	
				rules:
				{	
					ux_ddl_marker:
					{ 
						required: true
					},
					ux_txt_marker_name:
					{ 
						required: true
					}
				},
				errorPlacement: function(error, element)
				{
					jQuery(element).css("background-color","#FFCCCC");
					jQuery(element).css("border","1px solid red");
				},
				submitHandler: function(form)
				{
					overlay();
					var map_id = "<?php echo $map_id; ?>";
					var marker_name = encodeURIComponent(jQuery("#ux_txt_marker_name").val());
					<?php 
					if(isset($_REQUEST["mid"]))
					{
						?>
						var marker_id = "<?php echo intval($_REQUEST["mid"])?>";
						jQuery.post(ajaxurl, jQuery("#frm_edit_marker").serialize() +"&map_id="+map_id+"&marker_id="+marker_id+"&marker_name="+marker_name+"&param=update_marker_db&action=add_map_library&_wpnonce=<?php echo $update_marker;?>", function(data)
						{
							setTimeout(function () {
								jQuery(".loader_opacity").remove();
								jQuery(".opacity_overlay").remove();
								window.location.href = "admin.php?page=manage_map&map_id="+map_id;
							}, 2000);
						});
						<?php
					}
					else
					{
						?>
						jQuery.post(ajaxurl, jQuery("#frm_edit_marker").serialize() +"&map_id="+map_id+"&marker_name="+marker_name+"&param=add_marker_db&action=add_map_library&_wpnonce=<?php echo $add_new_marker;?>", function(data)
						{
							marker_count = 0;
							window.location.href = "admin.php?page=gmb_edit_marker&map_id="+map_id;
							jQuery(".loader_opacity").remove();
							jQuery(".opacity_overlay").remove();;
						});
						<?php
					}
					?>
				}
			});
			
			
			function overlay()
			{
				var overlay_opacity = jQuery("<div class=\"opacity_overlay\"></div>");
				jQuery("body").append(overlay_opacity);
				var overlay = jQuery("<div class=\"loader_opacity\"><div class=\"processing_overlay\"></div></div>");
				jQuery("body").append(overlay);
			}
			
			function proceed_to_back()
			{
				overlay();
				setTimeout(function () {
					jQuery(".loader_opacity").remove();
					jQuery(".opacity_overlay").remove();
					window.location.href = "admin.php?page=manage_map";
				}, 2000);
			}
		
			function error_message_close()
			{
				jQuery("#top-error").remove();
			}
			
			function marker_change_catgory()
			{
				var marker_category_selected = jQuery("#ux_ddl_Marker").val();
				jQuery("#show_map_icons").css("display","block");
				switch(parseInt(marker_category_selected))
				{
					case 1:
						jQuery("#ux_img_culture").css("display","block");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 2:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","block");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 3:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","block");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 4:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","block");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 5:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","block");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 6:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","block");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 7:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","block");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 8:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","block");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 9:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","block");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 10:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","block");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 11:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","block");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 12:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","block");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 13:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","block");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 14:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","block");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 15:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","block");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 16:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","block");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 17:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","block");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 18:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","block");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 19:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","block");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 20:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","block");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 21:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","block");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 22:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","block");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 23:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","block");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 24:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","block");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 25:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","block");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 26:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","block");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 27:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","block");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 28:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","block");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 29:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","block");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 30:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","block");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 31:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","block");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 32:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","block");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 33:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","block");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 34:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","block");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 35:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","block");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#upload_marker").css("display","none");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 36:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","block");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#upload_marker").css("display","none");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 37:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","block");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#upload_marker").css("display","none");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 38:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","block");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#upload_marker").css("display","none");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 39:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","block");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#upload_marker").css("display","none");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 40:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","block");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#upload_marker").css("display","none");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 41:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","block");
						jQuery("#upload_img").css("display","block");
						jQuery("#upload_marker").css("display","none");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					case 42:
						jQuery("#ux_img_culture").css("display","none");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_img").css("display","block");
						jQuery("#upload_marker").css("display","none");
						jQuery("#show_map_icons").css("display","none");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","block");
						jQuery("#upload_img_div").css("display","block");
					break;
					default:
						jQuery("#upload_img").css("display","none");
						jQuery("#show_map_icons").css("display","block");
						jQuery("#ux_img_culture").css("display","block");
						jQuery("#ux_img_entertainment").css("display","none");
						jQuery("#ux_img_crime").css("display","none");
						jQuery("#ux_img_natural_disaster").css("display","none");
						jQuery("#ux_img_education").css("display","none");
						jQuery("#ux_img_health").css("display","none");
						jQuery("#ux_img_electric_power").css("display","none");
						jQuery("#ux_img_military").css("display","none");
						jQuery("#ux_img_miscellaneous").css("display","none");
						jQuery("#ux_img_media").css("display","none");
						jQuery("#ux_img_days").css("display","none");
						jQuery("#ux_img_numbers").css("display","none");
						jQuery("#ux_img_letters").css("display","none");
						jQuery("#ux_img_special_characters").css("display","none");
						jQuery("#ux_img_agriculture").css("display","none");
						jQuery("#ux_img_animals").css("display","none");
						jQuery("#ux_img_natural_marvels").css("display","none");
						jQuery("#ux_img_weather").css("display","none");
						jQuery("#ux_img_city_services").css("display","none");
						jQuery("#ux_img_interior").css("display","none");
						jQuery("#ux_img_real_estate").css("display","none");
						jQuery("#ux_img_kids").css("display","none");
						jQuery("#ux_img_people").css("display","none");
						jQuery("#ux_img_home").css("display","none");
						jQuery("#ux_img_bars").css("display","none");
						jQuery("#ux_img_hotels").css("display","none");
						jQuery("#ux_img_restaurant").css("display","none");
						jQuery("#ux_img_takeaway").css("display","none");
						jQuery("#ux_img_sports").css("display","none");
						jQuery("#ux_img_apparels").css("display","none");
						jQuery("#ux_img_brands_chains").css("display","none");
						jQuery("#ux_img_computer_electronics").css("display","none");
						jQuery("#ux_img_food_drinks").css("display","none");
						jQuery("#ux_img_general_merchandise").css("display","none");
						jQuery("#ux_img_arial_transportation").css("display","none");
						jQuery("#ux_img_directions").css("display","none");
						jQuery("#ux_img_other_transportation").css("display","none");
						jQuery("#ux_img_road_signs").css("display","none");
						jQuery("#ux_img_road_transportation").css("display","none");
						jQuery("#ux_img_religion").css("display","none");
						jQuery("#ux_img_tourism").css("display","none");
						jQuery("#upload_marker").css("display","none");
						jQuery("#image_show").css("display","block");
						jQuery("#Or_marker").css("display","none");
						jQuery("#upload_img_div").css("display","none");
					break;
				}
			}
		</script>
		<?php
		if(file_exists(MAP_BK_PLUGIN_DIR ."/views/map-preview.php"))
		{
			include_once MAP_BK_PLUGIN_DIR ."/views/map-preview.php";
		}
	}
}
?>