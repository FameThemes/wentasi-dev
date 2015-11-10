<?php

define( 'REPEATABLE_CONTROL_URL', get_template_directory_uri().'/inc/customizer/repeatable/' );



function sanitize_repeatable_data_field( $input ){

    $input = json_decode( $input, true );
    $input = wp_parse_args( $input, array( 'data'=>'', 'fields'=> array() ) );
    $fields = $input['fields'];
    $data = wp_parse_args( $input['data'], array() );
    $data =  isset( $data['_items'] ) ? $data['_items'] : false;
    if ( ! $data ) {
        return false;
    }
    foreach( $data as $i => $item_data ){
        foreach( $item_data as $id => $value ){

            if ( isset( $fields[ $id ] ) ){

                switch( strtolower( $fields[ $id ]['type'] ) ) {
                    case 'text':
                        $data[ $i ][ $id ] = sanitize_text_field( $value );
                        break;
                    case 'textarea':
                        $data[ $i ][ $id ] = wp_kses_post( $value );
                        break;
                    case 'color':
                        $data[ $i ][ $id ] = sanitize_hex_color_no_hash( $value );
                        break;
                    case 'checkbox':
                        $data[ $i ][ $id ] = sanitize_text_field( $value );
                        break;
                    case 'select':
                        if ( is_array( $value ) ){
                            foreach( $value as $k => $v ){
                                $value [ $k ] =  sanitize_text_field( $v );
                            }
                            $data[ $i ][ $id ] = $value;
                        }else {
                            $data[ $i ][ $id ] = sanitize_text_field( $value );
                        }
                        break;
                    case 'radio':
                        $data[ $i ][ $id ] = sanitize_text_field( $value );
                        break;
                    case 'media':
                        $data[ $i ][ $id ]['url'] = sanitize_text_field( $value['url'] );
                        $data[ $i ][ $id ]['id']  = sanitize_text_field( $value['id'] );
                        break;
                    default:
                        $data[ $i ][ $id ] = wp_kses_post( $value );
                }

            }else {
                $data[ $i ][ $id ] = wp_kses_post( $value );
            }

        }
    }

    return $data;
}


/**
 * Typography control class.
 *
 * @since  1.0.0
 * @access public
 */
class WP_Customize_Repeatable_Control extends WP_Customize_Control {

    /**
     * The type of customize control being rendered.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $type = 'repeatable';

    // public $fields = array();

    public $fields = array();
    public $live_title_id = null;
    public $title_format = null;


    public function __construct( $manager, $id, $args = array() )
    {
        parent::__construct( $manager, $id, $args);
        if ( empty( $args['fields'] ) || ! is_array( $args['fields'] ) ) {
            $args['fields'] = array();
        }

        foreach ( $args['fields'] as $key => $op ) {
            $args['fields'][ $key ]['id'] = $key;
            if( ! isset( $op['value'] ) ) {
                if( isset( $op['default'] ) ) {
                    $args['fields'][ $key ]['value'] = $op['default'];
                } else {
                    $args['fields'][ $key ]['value'] = '';
                }
            }
        }

        $this->fields = $args['fields'];
        $this->live_title_id = $args['live_title_id'];
        if ( isset( $args['title_format'] ) && $args['title_format'] != '' ) {
            $this->title_format = $args['title_format'];
        }


    }

    public function to_json() {
        parent::to_json();
        $this->json['live_title_id'] = $this->live_title_id;
        $this->json['title_format'] = $this->title_format;
        $this->json['value']   = $this->value();
        $this->json['fields'] = $this->fields;

    }


    /**
     * Enqueue scripts/styles.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function enqueue() {

        wp_enqueue_media();

        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style( 'wp-color-picker' );

        wp_register_script( 'repeatable-controls', esc_url( REPEATABLE_CONTROL_URL . 'js/repeatable-controls.js' ), array( 'customize-controls' ) );
        wp_register_style( 'repeatable-controls', esc_url( REPEATABLE_CONTROL_URL . 'css/repeatable-controls.css' ) );

        wp_enqueue_script( 'repeatable-controls' );
        wp_enqueue_style( 'repeatable-controls' );
    }



    public function render_content() {
        ?>

        <label>
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif; ?>
            <?php if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
        </label>

        <input data-hidden-value type="hidden" <?php $this->input_attrs(); ?> value="" <?php $this->link(); ?> />

        <div class="form-data">
            <ul class="list-repeatable">
            </ul>
        </div>

        <div class="repeatable-actions">
            <span data-tpl-id="<?php echo esc_attr( $tpl_id ); ?>" class="button-secondary add-new-repeat-item"><?php _e( 'Add a Item' ) ?></span>
        </div>

         <script type="text/html" class="repeatable-js-template">
            <?php $this->js_item(); ?>
        </script>
        <?php
    }


    public function js_item( ){

        ?>
        <li class="repeatable-customize-control">
            <div class="widget">
                <div class="widget-top">
                    <div class="widget-title-action">
                        <a class="widget-action" href="#"></a>
                    </div>
                    <div class="widget-title">
                        <h4 class="live-title"><?php _e( '[Untitled]' ); ?></h4>
                    </div>
                </div>

                <div class="widget-inside">

                    <div class="form">
                        <div class="widget-content">

                            <# for ( i in data ) { #>
                                <# if ( ! data.hasOwnProperty( i ) ) continue; #>
                                <# field = data[i]; #>
                                <# if ( ! field.type ) continue; #>


                                <# if ( field.type ){ #>

                                    <div class="item item-{{{ field.type }}} item-{{{ field.id }}}">

                                        <# if ( field.type !== 'checkbox' ) { #>
                                            <# if ( field.title ) { #>
                                            <label class="field-label">{{ field.title }}</label>
                                            <# } #>

                                            <# if ( field.desc ) { #>
                                            <p class="field-desc description">{{ field.desc }}</p>
                                            <# } #>
                                        <# } #>


                                        <# if ( field.type === 'text' ) { #>

                                            <input data-live-id="{{{ field.id }}}" type="text" value="{{ field.value }}" data-repeat-name="_items[__i__][{{ field.id }}]" class="">

                                        <# } else if ( field.type === 'checkbox' ) { #>

                                            <# if ( field.title ) { #>
                                                <label class="checkbox-label">
                                                    <input type="checkbox" <# if ( field.value ) { #> checked="checked" <# } #> value="1" data-repeat-name="_items[__i__][{{ field.id }}]" class="">
                                                    {{ field.title }}</label>
                                            <# } #>

                                            <# if ( field.desc ) { #>
                                            <p class="field-desc description">{{ field.desc }}</p>
                                            <# } #>


                                        <# } else if ( field.type === 'select' ) { #>

                                            <# if ( field.multiple ) { #>
                                                <select multiple="multiple" data-repeat-name="_items[__i__][{{ field.id }}][]">
                                            <# } else  { #>
                                                <select data-repeat-name="_items[__i__][{{ field.id }}]">
                                            <# } #>

                                                <# for ( k in field.options ) { #>

                                                    <# if ( _.isArray( field.value ) ) { #>
                                                        <option <# if ( _.contains( field.value , k ) ) { #> selected="selected" <# } #>  value="{{ k }}">{{ field.options[k] }}</option>
                                                    <# } else { #>
                                                        <option <# if ( field.value == k ) { #> selected="selected" <# } #>  value="{{ k }}">{{ field.options[k] }}</option>
                                                    <# } #>

                                                <# } #>

                                            </select>

                                        <# } else if ( field.type === 'radio' ) { #>

                                            <# for ( k in field.options ) { #>

                                                <# if ( field.options.hasOwnProperty( k ) ) { #>

                                                    <label>
                                                        <input type="radio" <# if ( field.value == k ) { #> checked="checked" <# } #> value="{{ k }}" data-repeat-name="_items[__i__][{{ field.id }}]" class="widefat">
                                                        {{ field.options[k] }}
                                                    </label>

                                                <# } #>
                                            <# } #>

                                        <# } else if ( field.type == 'color' ) { #>

                                            <input type="text" value="{{ field.value }}" data-repeat-name="_items[__i__][{{ field.id }}]" class="color-field">

                                        <# } else if ( field.type == 'media' ) { #>

                                            <input type="hidden" value="{{ field.value.url }}" data-repeat-name="_items[__i__][{{ field.id }}][url]" class="image_url widefat">
                                            <input type="hidden" value="{{ field.value.id }}" data-repeat-name="_items[__i__][{{ field.id }}][id]" class="image_id widefat">

                                            <div class="current <# if ( field.value.url !== '' ){ #> show <# } #>">
                                                <div class="container">
                                                    <div class="attachment-media-view attachment-media-view-image landscape">
                                                        <div class="thumbnail thumbnail-image">
                                                            <# if ( field.value.url !== '' ){ #>
                                                                <img src="{{ field.value.url }}" alt="">
                                                            <# } #>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="actions">
                                                <button class="button remove-button " <# if ( field.value.url !== '' ){ #> style="display:none"; <# } #> type="button"><?php _e( 'Remove' ) ?></button>

                                                <button class="button upload-button" type="button"><?php _e( 'Change Image' ) ?></button>
                                                <div style="clear:both"></div>
                                            </div>


                                        <# } else if ( field.type == 'textarea' ) { #>

                                            <textarea data-live-id="{{{ field.id }}}" data-repeat-name="_items[__i__][{{ field.id }}]">{{ field.value }}</textarea>

                                        <# } #>

                                    </div>


                                <# } #>
                            <# } #>


                            <div class="widget-control-actions">
                                <div class="alignleft">
                                    <a href="#" class="repeat-control-remove" title=""><?php _e( 'Remove' ); ?></a> |
                                    <a href="#" class="repeat-control-close"><?php _e( 'Close' ); ?></a>
                                </div>
                                <br class="clear">
                            </div>

                        </div>
                    </div><!-- .form -->

                </div>

            </div>
        </li>
        <?php

    }


}





# Load scripts and styles.
// add_action( 'customize_preview_init',             'repeatable_customize_preview_enqueue_scripts'   );


/**
 * Load preview scripts/styles.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function repeatable_customize_preview_enqueue_scripts() {
	wp_enqueue_script( 'repeatable-customize-preview', esc_url( REPEATABLE_CONTROL_URL . 'js/repeatable-preview.js' ), array( 'jquery' ) );
}
