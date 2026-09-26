<?php
namespace BP3D\Base;

class ExtendMimeType {

    function register(){
        global $wp_version;
        add_filter( 'upload_mimes', [$this, 'bplugins_stp_mime_types'] );

        if ( version_compare( $wp_version, '5.1') >= 0):
            add_filter( 'wp_check_filetype_and_ext', [$this, 'bplugins_stp_add_allow_upload_extension_exception'], 10, 5);
        else:
            add_filter( 'wp_check_filetype_and_ext', [$this, 'bplugins_stp_add_allow_upload_extension_exception'], 10, 4);
        endif;
    }

    function bplugins_stp_mime_types( $mimes ) {
        // New allowed mime types.
        $mimes['glb'] = 'model/gltf-binary';
        $mimes['gltf'] = 'model/gltf-binary';
        return $mimes;
    }

    function bplugins_stp_add_allow_upload_extension_exception($data, $file, $filename,$mimes,$real_mime=null){
        // If file extension is 2 or more 
        $f_sp = explode(".", $filename);
        $f_exp_count  = count ($f_sp);
    
        if($f_exp_count <= 1){
            return $data;
        }else{
            $f_name = $f_sp[0];
            $ext  = $f_sp[$f_exp_count - 1];
        }
    
        if($ext == 'glb'){
            $type = 'model/gltf-binary';
            $proper_filename = '';
            return compact('ext', 'type', 'proper_filename');
        }if($ext == 'gltf'){
            $type = 'model/gltf-binary';
            $proper_filename = '';
            return compact('ext', 'type', 'proper_filename');
        }else {
            return $data;
        }
    }
}