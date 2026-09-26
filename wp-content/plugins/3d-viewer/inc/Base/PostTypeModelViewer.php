<?php
namespace BP3D\Base;

class PostTypeModelViewer{

    protected $post_type = 'bp3d-model-viewer';

    public function register(){
        add_action('init',[$this, 'registerPostType']);
        
        if ( is_admin() ) {
            add_action('manage_'.$this->post_type.'_posts_custom_column', [$this, 'addShortcodeColumn'], 10, 2);
            add_action('manage_'.$this->post_type.'_posts_columns', [$this, 'addShortcodeColumnContent'],10,2);
            // add_action('use_block_editor_for_post', [$this, 'use_block_editor_for_post'], 999, 2);
            add_filter('post_updated_messages', [$this, 'changeUpdateMessage']);
            add_action( 'admin_head-post.php', [$this, 'bp3d_hide_publishing_actions'] );
            add_action( 'admin_head-post-new.php', [$this, 'bp3d_hide_publishing_actions'] );
            add_filter( 'gettext', [$this, 'bp3d_change_publish_button'], 10, 2 );
            add_action( 'edit_form_after_title', [$this, 'bp3d_shortcode_area'] );
            add_filter( 'post_row_actions', [$this, 'bp3d_remove_row_actions'], 10, 2 );
        }
    }

    public function use_block_editor_for_post($use, $post){
        if ($this->post_type === $post->post_type) {
            return true;
        }
        return $use;
    }

    public function changeUpdateMessage($messages){
        $messages[$this->post_type][1] = __('Model Updated', 'b3d-viewer-lite');
        return $messages;
    }

    public function addShortcodeColumnContent($defaults){
        unset($defaults['date']);
        $defaults['shortcode'] = 'ShortCode';
        $defaults['date'] = 'Date';
        return $defaults;
    }		

    public function addShortcodeColumn($column_name, $post_ID){
        if ($column_name === 'shortcode') {
            echo "<div class='b3dviewer_front_shortcode'><input value='[3d_viewer id=$post_ID]' ><span class='htooltip'>". esc_html__("Copy To Clipboard", "b3d-viewer-lite")."</span></div>";
        }
    }

    public function registerPostType(){
        register_post_type( $this->post_type,
            array(
                'labels' => array(
                    'name'           => __( '3D Viewer', 'model-viewer' ),
                    'menu_name'      => __( '3D Viewer', 'model-viewer' ),
                    'name_admin_bar' => __( '3D Viewer', 'model-viewer' ),
                    'add_new'        => __( 'Add New', 'model-viewer' ),
                    'add_new_item'   => __( 'Add New ', 'model-viewer' ),
                    'new_item'       => __( 'New 3D Viewer ', 'model-viewer' ),
                    'edit_item'      => __( 'Edit 3D Viewer ', 'model-viewer' ),
                    'view_item'      => __( 'View 3D Viewer ', 'model-viewer' ),
                    'all_items'      => __( 'All 3D Viewers', 'model-viewer' ),
                    'not_found'      => __( 'Sorry, we couldn\'t find the Feed you are looking for.' ),
                ),
                'description'     => __( '3D Viewer Options.', 'model-viewer' ),
                'public'          => false,
                'show_ui'         => true,
                'show_in_menu'    => true,
                'menu_icon'       => 'dashicons-format-image',
                'query_var'       => true,
                'rewrite'         => array('slug' => 'model-viewer'),
                'capability_type' => 'post',
                'has_archive'     => false,
                'hierarchical'    => false,
                'menu_position'   => 20,
                'supports'        => array( 'title' ),
            )
        );

    }

    // HIDE everything in PUBLISH metabox except Move to Trash & PUBLISH button
    public function bp3d_hide_publishing_actions() {
        global  $post ;
        if ( $post->post_type == $this->post_type ) {
            echo  ' <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    } </style> ' ;
        }
    }

    public function bp3d_change_publish_button( $translation, $text ) {
        if ( $this->post_type == get_post_type() ) {
            if ( $text == 'Publish' ) {
                return 'Save';
            }
        }
        return $translation;
    }

    /*-------------------------------------------------------------------------------*/
    /* Shortcode Generator area  .
       /*-------------------------------------------------------------------------------*/
   
    public function bp3d_shortcode_area() {
        global  $post ;
           
        if ( $post->post_type == 'bp3d-model-viewer' ) { ?>	
           <div class="bp3d_shortcode">
               <div class="shortcode-heading">
                   <div class="icon"><span class="dashicons dashicons-shortcode"></span> <?php 
               _e( "SHORTCODE", "model-viewer" ); ?></div>
                   <div class="text"> <a href="https://bplugins.com/support/" target="_blank"><?php 
               _e( "Supports", "model-viewer" ); ?></a></div>
               </div>
               <div class="shortcode-left">
                   <h3><?php  _e( "Shortcode", "model-viewer" ); ?></h3>
                   <p><?php  _e( "Copy and paste this shortcode into your posts, pages and widget:", "model-viewer" ); ?></p>
                   <div class="shortcode" selectable>[3d_viewer id="<?php  echo  esc_attr( $post->ID ); ?>"]</div>
               </div>
               <div class="shortcode-right">
                   <h3><?php  _e( "Template Include", "model-viewer" ); ?></h3>
                   <p><?php  _e( "Copy and paste the PHP code into your template file:", "model-viewer" ); ?></p>
                   <div class="shortcode">&lt;?php echo do_shortcode('[3d_viewer id="<?php  echo  esc_html( $post->ID ) ; ?>"]'); ?&gt;</div>
               </div>
           </div>
       <?php 
        }
       
    }

    // Hide & Disabled View, Quick Edit and Preview Button
    public function bp3d_remove_row_actions( $idtions ) {
        global  $post;
        if ( $post->post_type == 'bp3d-model-viewer' ) {
            unset( $idtions['view'] );
            unset( $idtions['inline hide-if-no-js'] );
        }
        return $idtions;
    }
}