function pippin_get_image_id($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
        return $attachment[0]; 
}

function insert_thumb_shortcode_function( $atts ) {
    $attributes = shortcode_atts( array(
        'id' => '' // attribute_name => default_value
    ), $atts );

    $return = '';

    $post = get_post($attributes['id']);

    if( !empty($post)) {

        $post_category_color = extra_get_post_category_color($post->ID);

        $categories = get_the_category($post->ID);

        if ( ! empty( $categories ) ) {
            $return .= '<a class="frc-category"' . 'style="color:' . $post_category_color .';' .'" ' . 'href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
        }

        $image = get_post_meta($post->ID,'hp-photo',true);
        $image_id = pippin_get_image_id($image);
        $image = wp_get_attachment_image_src($image_id, 'medium');

        $image2 = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
        $image4 = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID) , 'medium'); 

        // Custom title if exist, otherwise print default

            if( ! empty($image)) {
            	$return .= '<a class="frc-image" href="' . get_the_permalink($post->ID) . '"><figure style="background:' . $post_category_color . ';">' . '<img class="frcimage-spec-height" src="' .  $image_thumb[0] . '"/>';
        	}
        	else {
        		$return .= '<a class="frc-image" href="' . get_the_permalink($post->ID) . '"><figure style="background:' . $post_category_color . ';">' . '<img class="frcimage-spec-height" src="' .  $image4[0] . '"/>';

        	}
            $title = get_post_meta($post->ID,'hp-title',true);

            // Custom title if exist, otherwise print default
            if( ! empty($title)) {
            	$return .= '<figcaption><h3>' . $title . '</h3></figcaption></figure></a>';
        	}
        	else {
        		$return .= '<figcaption><h3>' .  $post->post_title . '</h3></figcaption></figure></a>';
        	}
        return $return;
    }


}
add_shortcode( 'insert_thumb', 'insert_thumb_shortcode_function' );
