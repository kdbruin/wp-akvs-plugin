<?php

class My_Public extends My_Plugin {
    protected function init() {
        //$this->add_filter( 'pre_get_posts', 'set_post_types' );
    }
    
    public function set_post_types( $query ) {
        $query->set( 'post_type', array( 'post', 'my_custom_post_type' ) );
        
        return $query;
    }
    
    public function say_hello( $name = '' ) {
        $greeting = 'hello';
        if( $name ) {
            $greeting .= ' ' . esc_attr( $name );
        }
        
        echo $greeting;
    }
}
