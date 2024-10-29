<?php

	/**
	 * Provide a admin area view for the plugin
	 *
	 * This file is used to markup the admin-facing aspects of the plugin.
	 *
	 * @link       http://acmemk.com
	 * @since      1.0.0
	 *
	 * @package    Acme_Amazing_Search
	 * @subpackage Acme_Amazing_Search/admin/partials
	 */
?>

<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
<div class="wrap">
	<form method="post" name="search_options" action="options.php">
		<?php
			//Grab all options
			$options = get_option($this->plugin_name);

			//Search Options:
			$cache = $options['cache'];
			$auto_cache = $options['auto_cache'];
			$cron_cache = $options['cron_cache'];
			$posts = $options['posts'];
			$pages = $options['pages'];
			$products = $options['products'];
			$sku = $options['aas_sku'];
			$post_type = $options['post_type'];
			$categories = $options['categories'];
			$tags =$options['tags'];
			$brands = $options['brands'];
			$product_categories = $options['product_categories'];
			$product_tags =$options['product_tags'];
			$terms =$options['terms'];
			$title = $options['title'];
			$excerpt = $options['excerpt'];
			$results = $options['results'];
			$trim = $options['trim'];
			$behaviour = $options['behaviour'];
			$show_all_text = $options['show_all_text'];
			$append_post_type = $options['append_post_type'];
			$show_title = $options['show_title'];
			$show_excerpt = $options['show_excerpt'];
			$show_taxonomy = $options['show_taxonomy'];
			$separator = $options['separator'];

			$post_type_list = $this->get_post_types();

		?>
		<?php
			settings_fields($this->plugin_name);
			do_settings_sections($this->plugin_name);
		?>



		<!-- Setup the search in products -->
		<h2><?php _e( "Main Settings", $this->plugin_name ); ?></h2>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Search in Posts',$this->plugin_name); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-posts">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-posts"
				       name="<?php echo $this->plugin_name; ?>[posts]" value="1" <?php checked($posts, 1); ?>/>
				<span><?php _e( "Search in Posts", $this->plugin_name ); ?></span>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Search in Pages',$this->plugin_name); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-pages">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-pages"
				       name="<?php echo $this->plugin_name; ?>[pages]" value="1" <?php checked($pages, 1); ?>/>
				<span><?php _e( "Search in Pages", $this->plugin_name ); ?></span>
			</label>
		</fieldset>
		<?php if(class_exists('WooCommerce')): ?>
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('Search in Products',$this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-products">
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-products"
					       name="<?php echo $this->plugin_name; ?>[products]" value="1" <?php checked($products, 1); ?>/>
					<span><?php _e( "Search in Products", $this->plugin_name ); ?></span>
				</label>
			</fieldset>
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('Search in SKU',$this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-aas_sku">
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-aas_sku"
					       name="<?php echo $this->plugin_name; ?>[aas_sku]" value="1" <?php checked($sku, 1); ?>/>
					<span><?php _e( "Search in SKU", $this->plugin_name ); ?></span>
				</label>
			</fieldset>
		<?php endif; ?>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e( 'Search in custom post-types:', $this->plugin_name ); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-post_type">
				<span><?php _e( 'Search in custom post-types:', $this->plugin_name ); ?></span>
				<input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-post_type"
				       name="<?php echo $this->plugin_name; ?>[post_type]" value="<?php if(!empty($post_type)) echo $post_type; ?>"/>
				<em><?php _e( '(Comma separated values with no spaces)', $this->plugin_name ); ?></em>
			</label>
		</fieldset>
		<!-- Setup the search in other positions -->
		<h4><?php _e( "Extend search:", $this->plugin_name ); ?></h4>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Search in Categories',$this->plugin_name); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-categories">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-categories"
				       name="<?php echo $this->plugin_name; ?>[categories]" value="1" <?php checked($categories, 1); ?>/>
				<span><?php _e( "Search in Categories", $this->plugin_name ); ?></span>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Search in Tags',$this->plugin_name); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-tags">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-tags"
				       name="<?php echo $this->plugin_name; ?>[tags]" value="1" <?php checked($tags, 1); ?>/>
				<span><?php _e( "Search in Tags", $this->plugin_name ); ?></span>
			</label>
		</fieldset>
		<?php if(class_exists('WooCommerce')): ?>
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('Search in Brands',$this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-brands">
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-brands"
					       name="<?php echo $this->plugin_name; ?>[brands]" value="1" <?php checked($brands, 1); ?>/>
					<span><?php _e( "Search in Brands", $this->plugin_name ); ?></span>
				</label>
			</fieldset>
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('Search in Product Categories',$this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-product_categories">
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-product_categories"
					       name="<?php echo $this->plugin_name; ?>[product_categories]" value="1" <?php checked($product_categories, 1); ?>/>
					<span><?php _e( "Search in Product Categories", $this->plugin_name ); ?></span>
				</label>
			</fieldset>
			<fieldset>
				<legend class="screen-reader-text"><span><?php _e('Search in Product Tags',$this->plugin_name); ?></span></legend>
				<label for="<?php echo $this->plugin_name; ?>-product_tags">
					<input type="checkbox" id="<?php echo $this->plugin_name; ?>-product_tags"
					       name="<?php echo $this->plugin_name; ?>[product_tags]" value="1" <?php checked($product_tags, 1); ?>/>
					<span><?php _e( "Search in Product Tags", $this->plugin_name ); ?></span>
				</label>
			</fieldset>
		<?php endif; ?>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e( 'Search in custom terms:', $this->plugin_name ); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-terms">
				<span><?php _e( 'Search in custom terms:', $this->plugin_name ); ?></span>
				<input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-terms"
				       name="<?php echo $this->plugin_name; ?>[terms]" value="<?php if(!empty($terms)) echo $terms; ?>"/>
				<em><?php _e( '(Comma separated values with no spaces)', $this->plugin_name ); ?></em>
			</label>
		</fieldset>
		<!-- Setup other search options -->
		<h4><?php _e( "Search Options:", $this->plugin_name ); ?></h4>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Search in Title',$this->plugin_name); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>title">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>title"
				       name="<?php echo $this->plugin_name; ?>[title]" value="1" <?php checked($title, 1); ?>/>
				<span><?php _e( "Search in Title", $this->plugin_name ); ?></span>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Search in Excerpt',$this->plugin_name); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>excerpt">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>excerpt"
				       name="<?php echo $this->plugin_name; ?>[excerpt]" value="1" <?php checked($excerpt, 1); ?>/>
				<span><?php _e( "Search in Excerpt", $this->plugin_name ); ?></span>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e( 'Show All behaviour:', $this->plugin_name ); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-behaviour">
				<span><?php _e( '<i>Show All</i> behaviour:', $this->plugin_name ); ?></span>
				<select id="<?php echo $this->plugin_name; ?>-behaviour" name="<?php echo $this->plugin_name; ?>[behaviour]" >
					<option <?php selected($behaviour, -1);?> value="-1"><?php _e('Do default WordPress search',$this->plugin_name); ?></option>
					<option <?php selected($behaviour, 1);?> value="1"><?php _e('Category/Tag unique result',$this->plugin_name); ?></option>
				</select>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e( 'WP Search in specific post_type:', $this->plugin_name ); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-append_post_type">
				<span><?php _e( 'WP Search in specific post_type:', $this->plugin_name ); ?></span>
				<select id="<?php echo $this->plugin_name; ?>-behaviour" name="<?php echo $this->plugin_name; ?>[append_post_type]" >
					<option <?php selected(__('default',$this->plugin_name), 0);?> value="0"><?php _e('default',$this->plugin_name); ?></option>
					<?php foreach($post_type_list as $id=>$pt): ?>
						<option <?php selected($pt, $append_post_type);?> value="<?php echo $pt;?>"><?php echo $pt; ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</fieldset>
		<!-- Search Results -->
		<h4><?php _e( "Search Results:", $this->plugin_name ); ?></h4>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e( 'Show all text:', $this->plugin_name ); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-show_all_text">
				<span><?php _e( 'Show all text:', $this->plugin_name ); ?></span>
				<input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-show_all_text"
				       name="<?php echo $this->plugin_name; ?>[show_all_text]" value="<?php if(!empty($show_all_text)) echo $show_all_text; ?>"/>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Show Title',$this->plugin_name); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-show_title">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-show_title"
				       name="<?php echo $this->plugin_name; ?>[show_title]" value="1" <?php checked($show_title, 1); ?>/>
				<span><?php _e( "Show Title", $this->plugin_name ); ?></span>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Show Excerpt',$this->plugin_name); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-show_excerpt">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-show_excerpt"
				       name="<?php echo $this->plugin_name; ?>[show_excerpt]" value="1" <?php checked($show_excerpt, 1); ?>/>
				<span><?php _e( "Show Excerpt", $this->plugin_name ); ?></span>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Show Taxonomy',$this->plugin_name); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-show_taxonomy">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-show_taxonomy"
				       name="<?php echo $this->plugin_name; ?>[show_taxonomy]" value="1" <?php checked($show_taxonomy, 1); ?>/>
				<span><?php _e( "Show Taxonomy", $this->plugin_name ); ?></span>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e( 'Number of results in drop-down:', $this->plugin_name ); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-results">
				<span><?php _e( 'Number of results in drop-down:', $this->plugin_name ); ?></span>
				<input type="text" class="small-text" id="<?php echo $this->plugin_name; ?>-results"
				       name="<?php echo $this->plugin_name; ?>[results]" value="<?php if(!empty($results)) echo $results; ?>"/>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e( 'Response trim size:', $this->plugin_name ); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-trim">
				<span><?php _e( 'Response trim size:', $this->plugin_name ); ?></span>
				<input type="text" class="small-text" id="<?php echo $this->plugin_name; ?>-trim"
				       name="<?php echo $this->plugin_name; ?>[trim]" value="<?php if(!empty($trim)) echo $trim; ?>"/>
			</label>
		</fieldset>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e( 'Separation String:', $this->plugin_name ); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-separator">
				<span><?php _e( 'Separation string', $this->plugin_name ); ?></span>
				<input type="text" class="small-text" id="<?php echo $this->plugin_name; ?>-separator"
				       name="<?php echo $this->plugin_name; ?>[separator]" value="<?php if(!empty($separator)) echo $separator; ?>"/>
			</label>
		</fieldset>
		<!-- cache control -->
		<h2><?php _e( "Cache Control:", $this->plugin_name ); ?></h2>
		<input type="hidden" id="<?php echo $this->plugin_name; ?>-cache"
		       name="<?php echo $this->plugin_name; ?>[cache]" value="<?php echo 1 + rand(); ?>"/>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Automatic Caching',$this->plugin_name); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-auto_cache">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-auto_cache"
				       name="<?php echo $this->plugin_name; ?>[auto_cache]" value="1" <?php checked($auto_cache, 1); ?>/>
				<span><?php _e( "Automatic Caching", $this->plugin_name ); ?></span>
			</label>
		</fieldset>
		<div class="notice notice-warning inline"><?php _e( 'Automatic Caching is triggered to Save Post hook. This is usually fine over most installations but may run into some 502 error or server timeout. This depends on Database Size and Server Environments. If you experience latency or slowness you can disable the auto caching and try manual caching down here.', $this->plugin_name );?></div>
		<fieldset>
			<legend class="screen-reader-text"><span><?php _e('Hourly',$this->plugin_name); ?></span></legend>
			<label for="<?php echo $this->plugin_name; ?>-cron_cache">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-cron_cache"
				       name="<?php echo $this->plugin_name; ?>[cron_cache]" value="1" <?php checked($cron_cache, 1); ?>/>
				<span><?php _e( "Hourly", $this->plugin_name ); ?></span>
			</label>
		</fieldset>
		<div class="notice notice-warning inline"><?php _e( 'This option will call a wp_cron job every hour that will update cache. Here is a list of cons for this choice:<ul>
<li>Does not run continuously, only on page load</li>
<li>A task scheduled at 2:00 PM may not run until 5:00 PM</li>
<li>DNS resolution may prevent WP-Cron from running (cluster example)</li>
<li>WordPress makes an HTTP call to wp-cron.php. Some server firewalls may block this type of external HTTP request</li>
<li>Site caching may prevent WP-Cron from running until the cached page is refreshed</li>
</ul>', $this->plugin_name );?></div>
		<!--<div>
			<h4><?php //_e('Current WP_CRON Status',$this->plugin_name); ?></h4>
			<div class="notice notice-info inline">
				<pre><?php //print_r(_get_cron_array()); ?></pre>
			</div>-->
		</div>
		<div id="aas_cache_content">
			<h4><?php _e('Cache Status:',$this->plugin_name);?></h4>
			<div class="notice notice-info inline">
				<?php echo apply_filters('cache_info_html',null); ?>
			</div>
		</div>
		<div class="spinner"></div>
		<fieldset>
			<input type="button" class="button-secondary" id="<?php echo $this->plugin_name; ?>-do_cache" value="<?php _e('Cache Now',$this->plugin_name); ?>" />
		</fieldset>
		<?php submit_button( __('Save all changes',$this->plugin_name), 'primary', 'submit', true ); ?>
	</form>
</div>
