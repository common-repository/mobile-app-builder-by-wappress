<?php

?>

<div class='ml-col-twothirds'>
            <p>You can use the editor to inject HTML, PHP, CSS and Javascript code in a number of positions within the post and page 
                screens. You can reference the current post id using $post->id.</p>
				
	        <p><em>Note: this is for developers and advanced users only.</em></p>
					
			<p>Need any help? <a class="ml-intercom" href="mailto:h89uu5zu@incoming.intercom.io">Contact our developers</a>.</p>
				

            <div class="ml-editor-controls">
                <select id="ml_admin_post_customization_select" name="ml_admin_post_customization_select">
                    <option value="">
                        Select a customization...
                    </option>
                    <?php foreach(wp2android_Admin::$editor_sections as $editor_key=>$editor_name): ?>
                    <option value='<?php echo esc_attr($editor_key); ?>'?>
                        <?php echo esc_html($editor_name); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <a href="#" class='button-primary ml-save-editor-btn'>Save</a>
            </div>