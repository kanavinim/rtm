<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.

//
// Metabox of the PAGE
// Set a unique slug-like ID
//
$prefix = '_bp3dimages_';

//
// Create a metabox
//
CSF::createMetabox( $prefix, array(
  'title'        => '3D Viewer Settings',
  'post_type'    => 'bp3d-model-viewer',
  'show_restore' => true,
) );


//
// section: cPlayer Single Audio
//
CSF::createSection( $prefix, array(
  'fields' => array(

    array(
      'id'           => 'bp_3d_src',
      'type'         => 'media',
      'button_title' => esc_html__('Upload Source', 'model-viewer'),
      'title'        => esc_html__('3D Source', 'model-viewer'),
      'desc'         => esc_html__('Upload or Select 3d object files. Supported file type: glb, glTF', 'model-viewer'),
    ),

    array(
      'id'           => 'bp_3d_width',
      'type'         => 'dimensions',
      'title'        => esc_html__('Width', 'model-viewer'),
      'desc'         => esc_html__('3D Viewer Width', 'model-viewer'),
      'default'  => array(
        'width'  => '100',
        'unit'   => '%',
      ),
      'height'   => false,
    ),
    array(
      'id'           => 'bp_3d_height',
      'type'         => 'dimensions',
      'title'        => esc_html__('Height', 'model-viewer'),
      'desc'         => esc_html__('3D Viewer height', 'model-viewer'),
      'units'        => ['px', 'em', 'pt'],
      'default'  => array(
        'height' => '320',
        'unit'   => 'px',
      ),
      'width'   => false,
    ),
    array(
      'id'       => 'bp_camera_control',
      'type'     => 'switcher',
      'title'    => esc_html__('Camera Controls', 'model-viewer'),
      'desc'     => esc_html__('Use the camera-controls attribute to enable user interaction', 'model-viewer'),
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default' => true,

    ),
    array(
      'id'        => 'bp_3d_zooming',
      'type'      => 'switcher',
      'title'     => 'Disable Zoom ?',
      'subtitle'  => esc_html__('Enable or Disable Zoom Behaviour', 'model-viewer'),
      'desc'      => esc_html__('If you wish to disable zooming behaviour please choose Yes.', 'model-viewer'),
      'text_on'   => 'Yes',
      'text_off'  => 'NO',
      'text_width'  => 60,
      'default'   => false,

    ),
    array(
      'id'         => 'bp_3d_loading',
      'type'       => 'radio',
      'title'      => esc_html__('Loading Type', 'model-viewer'),
      'subtitle'   => esc_html('Choose Loading type, default:  \'Auto\' ', 'model-viewer'),
      'options'    => array(
        'auto'  => 'Auto',
        'lazy'  => 'Lazy',
        'eager' => 'Eager',
      ),
      'default'    => 'auto',
    ),
    array(
      'id'       => 'bp_3d_rotate',
      'type'     => 'switcher',
      'title'    => esc_html__('Auto Rotate', 'model-viewer'),
      'desc'     => esc_html('Enables the auto-rotation of the model.', 'model-viewer'),
      'text_on'  => 'Yes',
      'text_off' => 'No',
      'default'  => true,
    ),
    
  ) // End fields


) );
