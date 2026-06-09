<?php

class cfs_code_view extends cfs_field
{

    function __construct() {
        $this->name = 'code_view';
        $this->label = __( 'Code View', 'cfs' );

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }


    function html( $field ) {
        $field->value = null === $field->value ? '' : $field->value;
    ?>
        <textarea name="<?php echo esc_attr( $field->input_name ); ?>" class="<?php echo esc_attr( $field->input_class ); ?>" rows="8" spellcheck="false"><?php echo esc_textarea( $field->value ); ?></textarea>
    <?php
    }


    function options_html( $key, $field ) {
    ?>
        <tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
            <td class="label">
                <label><?php _e( 'Default Value', 'cfs' ); ?></label>
            </td>
            <td>
                <?php
                    CFS()->create_field( [
                        'type' => 'textarea',
                        'input_name' => 'cfs[fields][' . absint( $key ) . '][options][default_value]',
                        'value' => $this->get_option( $field, 'default_value' ),
                    ] );
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
            <td class="label">
                <label><?php _e( 'Language', 'cfs' ); ?></label>
            </td>
            <td>
                <?php
                    CFS()->create_field( [
                        'type' => 'select',
                        'input_name' => 'cfs[fields][' . absint( $key ) . '][options][language]',
                        'options' => [
                            'choices' => [
                                'plain_text' => __( 'Plain text', 'cfs' ),
                                'html' => 'HTML',
                                'css' => 'CSS',
                                'javascript' => 'JavaScript',
                                'php' => 'PHP',
                                'bash' => 'Bash',
                                'json' => 'JSON',
                            ],
                            'force_single' => true,
                        ],
                        'value' => $this->get_option( $field, 'language', 'plain_text' ),
                    ] );
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
            <td class="label">
                <label><?php _e( 'Copy Button', 'cfs' ); ?></label>
            </td>
            <td>
                <?php
                    CFS()->create_field( [
                        'type' => 'true_false',
                        'input_name' => 'cfs[fields][' . absint( $key ) . '][options][copy_button]',
                        'input_class' => 'true_false',
                        'value' => $this->get_option( $field, 'copy_button', 1 ),
                        'options' => [ 'message' => __( 'Show copy button', 'cfs' ) ],
                    ] );
                ?>
            </td>
        </tr>
        <tr class="field_option field_option_<?php echo esc_attr( $this->name ); ?>">
            <td class="label">
                <label><?php _e( 'Validation', 'cfs' ); ?></label>
            </td>
            <td>
                <?php
                    CFS()->create_field( [
                        'type' => 'true_false',
                        'input_name' => 'cfs[fields][' . absint( $key ) . '][options][required]',
                        'input_class' => 'true_false',
                        'value' => $this->get_option( $field, 'required' ),
                        'options' => [ 'message' => __( 'This is a required field', 'cfs' ) ],
                    ] );
                ?>
            </td>
        </tr>
    <?php
    }


    function enqueue_assets() {
        wp_enqueue_style( 'cfs-code-view', CFS_URL . '/assets/css/code-view.css', [], CFS_VERSION );
        wp_enqueue_script( 'cfs-code-view', CFS_URL . '/assets/js/code-view.js', [], CFS_VERSION, true );
    }


    function pre_save( $value, $field = null ) {
        if ( is_array( $value ) ) {
            $value = reset( $value );
        }

        return is_scalar( $value ) ? (string) $value : '';
    }


    function format_value_for_api( $value, $field = null ) {
        if ( '' === (string) $value ) {
            return '';
        }

        $language = $this->get_option( $field, 'language', 'plain_text' );
        $language_class = sanitize_html_class( 'language-' . str_replace( '_', '-', $language ) );
        $copy_button = 0 < (int) $this->get_option( $field, 'copy_button', 1 );

        $button = '';
        if ( $copy_button ) {
            $button = sprintf(
                '<button type="button" class="cfs-code-view-copy" data-label="%1$s" data-copied="%2$s">%1$s</button>',
                esc_attr__( 'Copy', 'cfs' ),
                esc_attr__( 'Copied', 'cfs' )
            );
        }

        return sprintf(
            '<div class="cfs-code-view"><pre><code class="%1$s">%2$s</code></pre>%3$s</div>',
            esc_attr( $language_class ),
            esc_html( (string) $value ),
            $button
        );
    }
}
