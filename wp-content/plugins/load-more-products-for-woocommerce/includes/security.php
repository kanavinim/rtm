<?php

if ( ! class_exists( 'BeRocket_LMP_Security' ) ) {
    final class BeRocket_LMP_Security {
        public static function class_list( $value ) {
            if ( ! is_scalar( $value ) ) {
                return '';
            }

            $classes = preg_split( '/\s+/', trim( (string) $value ) );
            $classes = array_map( 'sanitize_html_class', (array) $classes );
            $classes = array_filter( $classes );

            return implode( ' ', array_unique( $classes ) );
        }

        public static function css_selector( $value ) {
            if ( ! is_scalar( $value ) ) {
                return '';
            }

            $value = wp_strip_all_tags( (string) $value, true );
            $value = preg_replace( '/[<{};@\\\\\x00-\x1F\x7F]/u', '', $value );

            return trim( (string) $value );
        }

        public static function style_block( $value ) {
            if ( ! is_scalar( $value ) ) {
                return '';
            }

            return str_replace( array( "\0", '<' ), '', wp_check_invalid_utf8( (string) $value ) );
        }

        public static function loading_image( $value ) {
            if ( ! is_scalar( $value ) ) {
                return '';
            }

            $value = trim( wp_strip_all_tags( (string) $value ) );
            if ( 0 === strpos( $value, 'fa-' ) ) {
                return sanitize_html_class( $value );
            }

            return esc_url_raw( $value );
        }

        public static function settings( $settings, $defaults = array() ) {
            if ( ! is_array( $settings ) ) {
                return array();
            }

            foreach ( array( 'br_lmp_button_settings', 'br_lmp_prev_settings' ) as $option_name ) {
                if ( empty( $settings[ $option_name ] ) || ! is_array( $settings[ $option_name ] ) ) {
                    continue;
                }

                $button = &$settings[ $option_name ];
                if ( array_key_exists( 'custom_class', $button ) ) {
                    $button['custom_class'] = self::class_list( $button['custom_class'] );
                }
                if ( array_key_exists( 'button_text', $button ) ) {
                    $button['button_text'] = is_scalar( $button['button_text'] )
                        ? wp_kses_post( (string) $button['button_text'] )
                        : '';
                }

                foreach ( array( 'image', 'image_hover', 'image_loading' ) as $image_key ) {
                    if ( array_key_exists( $image_key, $button ) ) {
                        $button[ $image_key ] = is_scalar( $button[ $image_key ] )
                            ? esc_url_raw( (string) $button[ $image_key ] )
                            : '';
                    }
                }

                foreach (
                    array(
                        'font-size',
                        'padding-left',
                        'padding-right',
                        'padding-top',
                        'padding-bottom',
                        'margin-left',
                        'margin-right',
                        'margin-top',
                        'margin-bottom',
                        'border-left',
                        'border-right',
                        'border-top',
                        'border-bottom',
                        'border-top-left-radius',
                        'border-top-right-radius',
                        'border-bottom-left-radius',
                        'border-bottom-right-radius',
                        'width',
                    ) as $number_key
                ) {
                    if ( array_key_exists( $number_key, $button ) ) {
                        $button[ $number_key ] = is_scalar( $button[ $number_key ] )
                            ? absint( $button[ $number_key ] )
                            : 0;
                    }
                }

                foreach ( array( 'background-color', 'color', 'border-color' ) as $color_key ) {
                    if ( ! array_key_exists( $color_key, $button ) ) {
                        continue;
                    }

                    $default = isset( $defaults[ $option_name ][ $color_key ] )
                        ? $defaults[ $option_name ][ $color_key ]
                        : '#000000';
                    $button[ $color_key ] = is_scalar( $button[ $color_key ] )
                        ? sanitize_hex_color( (string) $button[ $color_key ] )
                        : '';
                    if ( empty( $button[ $color_key ] ) ) {
                        $button[ $color_key ] = sanitize_hex_color( $default );
                    }
                }

                if ( isset( $button['hover'] ) && is_array( $button['hover'] ) ) {
                    foreach ( array( 'background-color', 'color' ) as $color_key ) {
                        if ( ! array_key_exists( $color_key, $button['hover'] ) ) {
                            continue;
                        }

                        $default = isset( $defaults[ $option_name ]['hover'][ $color_key ] )
                            ? $defaults[ $option_name ]['hover'][ $color_key ]
                            : '#000000';
                        $button['hover'][ $color_key ] = is_scalar( $button['hover'][ $color_key ] )
                            ? sanitize_hex_color( (string) $button['hover'][ $color_key ] )
                            : '';
                        if ( empty( $button['hover'][ $color_key ] ) ) {
                            $button['hover'][ $color_key ] = sanitize_hex_color( $default );
                        }
                    }
                }

                if ( isset( $button['loading_position'] ) && is_array( $button['loading_position'] ) ) {
                    foreach ( array( 'width', 'height', 'left', 'top' ) as $position_key ) {
                        if ( array_key_exists( $position_key, $button['loading_position'] ) ) {
                            $button['loading_position'][ $position_key ] = is_scalar( $button['loading_position'][ $position_key ] )
                                ? absint( $button['loading_position'][ $position_key ] )
                                : 0;
                        }
                    }

                    if ( isset( $button['loading_position']['position'] ) ) {
                        $position = is_scalar( $button['loading_position']['position'] )
                            ? sanitize_key( (string) $button['loading_position']['position'] )
                            : '';
                        $button['loading_position']['position'] = in_array( $position, array( 'none', 'left', 'right' ), true )
                            ? $position
                            : 'none';
                    }
                }

                unset( $button );
            }

            if ( isset( $settings['br_lmp_general_settings'] ) && is_array( $settings['br_lmp_general_settings'] ) ) {
                $general = &$settings['br_lmp_general_settings'];
                if ( array_key_exists( 'loading_image', $general ) ) {
                    $general['loading_image'] = self::loading_image( $general['loading_image'] );
                }
                foreach ( array( 'buffer', 'mobile_width' ) as $number_key ) {
                    if ( array_key_exists( $number_key, $general ) ) {
                        $general[ $number_key ] = is_scalar( $general[ $number_key ] )
                            ? absint( $general[ $number_key ] )
                            : 0;
                    }
                }
                unset( $general );
            }

            if ( isset( $settings['br_lmp_messages_settings'] ) && is_array( $settings['br_lmp_messages_settings'] ) ) {
                $messages = &$settings['br_lmp_messages_settings'];
                foreach ( array( 'loading_class', 'end_text_class' ) as $class_key ) {
                    if ( array_key_exists( $class_key, $messages ) ) {
                        $messages[ $class_key ] = self::class_list( $messages[ $class_key ] );
                    }
                }
                foreach ( array( 'loading', 'end_text' ) as $html_key ) {
                    if ( array_key_exists( $html_key, $messages ) ) {
                        $messages[ $html_key ] = is_scalar( $messages[ $html_key ] )
                            ? wp_kses_post( (string) $messages[ $html_key ] )
                            : '';
                    }
                }
                unset( $messages );
            }

            if ( isset( $settings['br_lmp_selectors_settings'] ) && is_array( $settings['br_lmp_selectors_settings'] ) ) {
                foreach ( array( 'products', 'item', 'pagination', 'next_page', 'prev_page' ) as $selector_key ) {
                    if ( array_key_exists( $selector_key, $settings['br_lmp_selectors_settings'] ) ) {
                        $settings['br_lmp_selectors_settings'][ $selector_key ] =
                            self::css_selector( $settings['br_lmp_selectors_settings'][ $selector_key ] );
                    }
                }
            }

            if (
                isset( $settings['br_lmp_lazy_load_settings'] )
                && is_array( $settings['br_lmp_lazy_load_settings'] )
                && isset( $settings['br_lmp_lazy_load_settings']['animation'] )
            ) {
                $settings['br_lmp_lazy_load_settings']['animation'] =
                    is_scalar( $settings['br_lmp_lazy_load_settings']['animation'] )
                        ? sanitize_html_class( (string) $settings['br_lmp_lazy_load_settings']['animation'] )
                        : '';
            }

            return $settings;
        }
    }
}
