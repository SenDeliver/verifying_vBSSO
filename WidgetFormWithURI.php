<?php
require_once __DIR__ . "/CheckingVBSSO.php";

class WidgetFormWithURI extends WP_Widget
{

    private function debug($obj)
    {
        echo "<pre>";
        var_dump($obj);
        echo "</pre>";
    }

    public function __construct() {
        parent::__construct(
            'widget_for_plugin', // Base ID
            esc_html__( 'Assistant for :Check_vBSSO', 'text_domain' ), // Name
            array( 'description' => esc_html__( 'Widget is designed to work with the plugin :Check vBSSO', 'text_domain' ), ) // Args
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        echo esc_html__( 'Enter the address and select the platform', 'text_domain' );
        echo
        '<form action="" method="get">
            <input name="URI" value="http://bitbstaking.net/forums" type="text">
                <select name="platform" id="">
                    <option value="vbulletin">vBulletin</option>
                    <option value="magento">Magento</option>
                    <option value="wordpress">WordPress</option>
                </select>
            <input type="submit">
         </form>';

        $check1 = new CheckingVBSSO();
        $check1->checkURI();

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Assistant for :vbsso_check', 'text_domain' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"><br><br>
            <!--<input type="checkbox" checked name="save_files" id="save_files" value="save"><label for="save_files">Will save logs in .csv?</label><br>-->
        </p>
        <?php
        //$this->debug($instance);
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

        return $instance;
    }

}

