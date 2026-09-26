<?php

namespace BP3D\Template;

class ModelViewer{

    public static function html($data){
        self::enqueueFile();
        ob_start();
        ?>
        <div id="<?php echo esc_attr($data['uniqueId']) ?>" class="b3dviewer align<?php echo esc_attr(self::i($data, 'align')) ?> <?php echo esc_attr($data['woo'] ? ' woocommerce' : '') ?>" > 
            <div id="<?php echo esc_attr(self::i($data['additional'], 'ID')) ?>" class="<?php echo esc_attr(self::i($data['additional'], 'Class')) ?> b3dviewer-wrapper <?php echo esc_attr(self::i($data, 'elementor', false) ? 'elementor': '') ?>">
                <style><?php echo esc_html($data['stylesheet']) ?></style>
                <?php 
                    $attribute = "exposure=".$data['exposure'];
                    if($data['mouseControl']){
                        $attribute .= ' camera-controls ';
                    }
                    if($data['autoRotate']){
                        $attribute .= ' auto-rotate ';
                    }
            
                    if($data['lazyLoad']){
                        $attribute .= "loading=lazy ";
                    }
            
                    if($data['shadow']){
                        $attribute .= " shadow-intensity=1 shadow-softness=1 ";
                    }
            
                    if($data['autoplay']){
                        $attribute .= " autoplay ";
                    }
                    if(!$data['multiple'] && $data['selectedAnimation']){
                        $attribute .= " data-animation=".$data['selectedAnimation']." animation-name=".$data['selectedAnimation']." ";
                    }

                    $cameraOrbit = $data['rotateAlongX']."deg ".$data['rotateAlongY']."deg 105% ";

                    if($data['multiple']){
                        $source = $data['models'][0]['modelUrl'];
                        $poster = $data['models'][0]['poster'];
                    }else {
                        $source = self::i($data['model'], 'modelUrl', '');
                        $poster = self::i($data['model'], 'poster', '');
                    }
                ?>

                <model-viewer 
                    data-js-focus-visible 
                    data-decoder="<?php echo esc_attr(self::i($data['model'], 'decoder', 'none')) ?>" <?php echo esc_attr($attribute); ?> 
                    poster="<?php echo esc_url($poster); ?>" 
                    src="<?php echo esc_url($source); ?>" 
                    alt="<?php esc_html_e("A 3D model", "b3d-viewer-lite") ?>"
                    <?php if($data['rotate']){ ?>
                        camera-orbit="<?php echo esc_attr($cameraOrbit) ?>"
                    <?php } ?>
                    class="<?php echo esc_attr($data['progressBar'] ? 'hide_progressbar' : 'hide_progressbar') ?>"
                >
                    <?php if($data['fullscreen']){ ?>
                        <svg id="openBtn" width="30px" height="30px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#f2f2f2" class="bi bi-arrows-fullscreen">
                            <path fill-rule="evenodd" d="M5.828 10.172a.5.5 0 0 0-.707 0l-4.096 4.096V11.5a.5.5 0 0 0-1 0v3.975a.5.5 0 0 0 .5.5H4.5a.5.5 0 0 0 0-1H1.732l4.096-4.096a.5.5 0 0 0 0-.707zm4.344 0a.5.5 0 0 1 .707 0l4.096 4.096V11.5a.5.5 0 1 1 1 0v3.975a.5.5 0 0 1-.5.5H11.5a.5.5 0 0 1 0-1h2.768l-4.096-4.096a.5.5 0 0 1 0-.707zm0-4.344a.5.5 0 0 0 .707 0l4.096-4.096V4.5a.5.5 0 1 0 1 0V.525a.5.5 0 0 0-.5-.5H11.5a.5.5 0 0 0 0 1h2.768l-4.096 4.096a.5.5 0 0 0 0 .707zm-4.344 0a.5.5 0 0 1-.707 0L1.025 1.732V4.5a.5.5 0 0 1-1 0V.525a.5.5 0 0 1 .5-.5H4.5a.5.5 0 0 1 0 1H1.732l4.096 4.096a.5.5 0 0 1 0 .707z"></path>
                        </svg>
                        <svg id="closeBtn" width="30px" height="30px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill="none" stroke="#333" stroke-width="2" d="M7,7 L17,17 M7,17 L17,7"></path>
                        </svg>
                    <?php } ?>

                    <?php if($data['variant']){ ?>
                        <div class="variantWrapper select">
                            <?php esc_html_e('Variant', 'b3d-viewer-lite') ?>: <select id="variant"></select>
                        </div>
                    <?php } ?>

                    <?php if($data['animation']){ ?>
                        <div class="animationWrapper select">
                            <?php esc_html_e('Animations', 'b3d-viewer-lite') ?>: <select id="animations"></select>
                        </div>
                    <?php } ?>
                    <?php if($data['loadingPercentage']){ ?>
                        <div class="percentageWrapper">
                            <div class="overlay"></div>
                            <span class="percentage">0%</span>
                        </div>
                    <?php } ?>

                    <?php if($data['multiple']){ ?>
                    <div class="slider">
                        <div class="slides">
                            <?php foreach($data['models'] as $key => $model){ ?>
                                <?php if($model){ ?>
                                    <button class="slide <?php echo esc_attr($key === 0 ? 'selected' : '') ?>" data-source="<?php echo esc_url($model['modelUrl']) ?>" data-poster="<?php echo esc_url(self::i($model, 'poster', '')) ?>"> 
                                    <img src="<?php echo esc_url(self::i($model, 'poster', '')) ?>" /> 
                                    </button>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </model-viewer>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }

    /**
     * enqueue essential file
     */
    public static function enqueueFile(){
        wp_enqueue_script('bp3d-public');
        wp_enqueue_style('bp3d-public');
    }


    /**
     * return value if it isset
     */
    public static function i($array = [], $index = ''){
        if(isset($array[$index])){
            return $array[$index];
        }
        return false;
    }

}

