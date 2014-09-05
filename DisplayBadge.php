<?php
/*
    Plugin Name: Display Badge
    Plugin URI: http://blog.damn.org.za/widgets/
    Description: Individual Badge Version of DisplayBadges
    Author: Eugéne Roux
    Version: 1.2
    Author URI: http://damn.org.za/
 */

//
//  DisplayBadge Class
//
class DisplayBadge extends WP_Widget {
    //
    //  constructor
    //
    public function DisplayBadge() {
        $widget_ops = array( 'classname' => 'widget_badges', 'description' => __( "Display a Badge within the widget in the style of 'DisplayBadges' for those time you only need to display a few or don't have the inclination of creating them as files." ));
        $this->WP_Widget( 'badge', __( 'Badge', 'badge_widget' ), $widget_ops );
        $this->widget_defaults = array(
            'padbadge' => true,
            'displayframe' => true,
            'dropshadow' => false,
            'destURL' => '',
            'destIMG' => '',
            'badgeNote' => '',
        );

    }

    //
    //  @see WP_Widget::widget
    //
    public function widget($args, $instance) {
        extract( $args );
        $id = $args['widget_id'];
        $title = apply_filters('widget_title', $instance['title']);
        $destURL = $instance['destURL'];
        $destIMG = $instance['destIMG'];
        $badgeNote = $instance['badgeNote'];
        $dropshadow = $instance["dropshadow"] ? true : false;
        $padbadge = $instance["padbadge"] ? true : false;
        $displayframe = $instance["displayframe"] ? true : false;

        if ( $destIMG ) {
            // Anything to see here?

            echo $before_widget;

            if ( $title )
                echo $before_title . $title . $after_title; // This way we get to choose a "No Title" scenario...

            print( "\n\t<div id='inner-" . $id . "' class='badge' " );

            if ( $padbadge || $displayframe || $dropshadow  ) {
                print( "style='" );
                if ( $padbadge ) {
                    print( "text-align: center; width: auto; margin: 1em; padding: 2ex; " );
                }
                if ( $displayframe ) {
                    print( "border: 1px solid; " );
                    print( "-moz-border-radius: 1em; -webkit-border-radius: 1em; -khtml-border-radius: 1em; border-radius: 1em; " );
                }
                if ( $dropshadow ) {
                    print( "-moz-box-shadow: #CCC 5px 5px 5px; -webkit-box-shadow: #CCC 5px 5px 5px; ");
                    print( "-khtml-box-shadow: #CCC 5px 5px 5px; box-shadow: #CCC 5px 5px 5px; ");
                }
                print( "' " );
            }

            print( ">\n" );

            if ( $title) {
                print( "\t\t<!-- " . $title . " -->\n" );
            }

            if ( $destURL ) {   // Link will link if the link is linketey. Or something.
                print( "\t\t<div onclick=\"location.href='" . $destURL . "';\" style='cursor: pointer;'>\n" );
            }

            print( "\t\t\t<img alt='" . $destURL . "' " );

            if ( $title ) {
                print( " title='" . $title . "' " );
            }

            print( "src=' " . $destIMG . " ' />\n" );

            if ( $badgeNote ) {
                print( "\t\t\t<br />\n\t\t\t" . $badgeNote . "\n" );
            }

            print( "\t\t</div>\n" );

            if ( $title) {
                print( "\t\t<!-- /" . $title . " -->\n" );
            }
        }
    }

    //
    //  @see WP_Widget::update
    //
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['destURL'] = $new_instance['destURL'];
        $instance['destIMG'] = $new_instance['destIMG'];
        $instance['badgeNote'] = $new_instance['badgeNote'];
        $instance['displayframe'] = ( isset( $new_instance['displayframe'] ) ? 1 : 0 );
        $instance['padbadge'] = ( isset( $new_instance['padbadge'] ) ? 1 : 0 );
        $instance['dropshadow'] = ( isset( $new_instance['dropshadow'] ) ? 1 : 0 );
        return $instance;
    }

    //
    //  @see WP_Widget::form
    //
    public function form( $instance ) {
        $instance = wp_parse_args( $instance, $this->widget_defaults );
        extract( $instance );

        $title = esc_attr( $instance['title'] );
        $destURL = $instance['destURL'];
        $destIMG = $instance['destIMG'];
        $badgeNote = $instance['badgeNote'];
        $displayframe = $instance['displayframe'] ? "checked='checked'" : "";
        $padbadge = $instance['padbadge'] ? "checked='checked'" : "";
        $dropshadow = $instance['dropshadow'] ? "checked='checked'" : "";

        print( "\t<p>\n\t\t<label for='" . $this->get_field_id("title") . "'>" ); _e( "Title:" );
        print( "\n\t\t\t<input class='widefat' id='" . $this->get_field_id("title") . "' name='" . $this->get_field_name("title") . "' type='text' value='" . $title . "' />\n\t\t</label>\n\t\t<em>May be left empty for no title</em>\n\t</p>\n" );

        print( "\t<p>\n\t\t<label for='" . $this->get_field_id("destURL") . "'>"); _e( "Link to Web Site:" );
        print( "\n\t\t\t<input class='widefat' id='" . $this->get_field_id("destURL") . "' name='" . $this->get_field_name("destURL") . "' type='text' value='" . $destURL . "' />\n\t\t</label>\n\t\t\t<em>Optional</em>\n\t</p>\n" );

        print( "\t<p>\n\t\t<label for='" . $this->get_field_id("destIMG") . "'>"); _e( "Link to Image:" );
        print( "\n\t\t\t<input class='widefat' id='" . $this->get_field_id("destIMG") . "' name='" . $this->get_field_name("destIMG") . "' type='text' value='" . $destIMG . "' />\n\t\t</label>\n\t\t<em><q>Standard</q> seems to be 88x31 pixels&hellip;</em>\n\t</p>\n" );

        print( "\t<p>\n\t\t<label for='" . $this->get_field_id("badgeNote") . "'>"); _e( "Note:" );
        print( "\n\t\t\t<input class='widefat' id='" . $this->get_field_id("badgeNote") . "' name='" . $this->get_field_name("badgeNote") . "' type='text' value='" . $badgeNote . "' />\n\t\t</label>\n\t\t<em>A short description; may be left empty</em>\n\t</p>\n" );

        print( "\t<p>\n" );
        print( "\t\t<input class='checkbox' type='checkbox' " . $padbadge );
        print( " id='" . $this->get_field_id("padbadge") . "' name='" . $this->get_field_name("padbadge") . "'/>\n" );
        print( "\t\t<label for='" . $this->get_field_id("padbadge") . "'>" ); _e( "Pad the Badge Display" );
        print( "</label>\n" );
        print( "\t\t<br />\n" );
        print( "\t\t<input class='checkbox' type='checkbox' " . $displayframe );
        print( " id='" . $this->get_field_id("displayframe") . "' name='" . $this->get_field_name("displayframe") . "'/>\n" );
        print( "\t\t<label for='" . $this->get_field_id("displayframe") . "'>" ); _e( "Display Badge in a Box" );
        print( "</label>\n" );
        print( "\t\t<br />\n" );
        print( "\t\t<input class='checkbox' type='checkbox' " . $dropshadow );
        print( " id='" . $this->get_field_id("dropshadow") . "' name='" . $this->get_field_name("dropshadow") . "'/>\n" );
        print( "\t\t<label for='" . $this->get_field_id("dropshadow") . "'>" ); _e( "Display a Drop-Shadow" );
        print( "</label>\n" );
        print( "\t</p>\n" );

    }
}

//
// register DisplayBadge widget
//
add_action('widgets_init', create_function('', 'return register_widget( "DisplayBadge" );'));

