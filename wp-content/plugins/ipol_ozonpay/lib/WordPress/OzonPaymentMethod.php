<?php

namespace Ipol\OzonPay\WordPress;

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

class OzonPaymentMethod extends AbstractPaymentMethodType
{
    protected $name = 'ozonpay';

    /**
     * @var OzonPayment
     */
    private $gateway;

    public function initialize()
    {
        $this->gateway = new OzonPayment();
    }

    public function is_active()
    {
        return $this->gateway->is_available();
    }

    public function get_payment_method_script_handles() {
        $handle = $this->name . '-block';
        wp_register_script(
            $handle,
            plugin_dir_url(OzonPayPlugin::getPluginDir()) . 'ipol_ozonpay/assets/js/checkout.js'
        );
        return [$handle];
    }

    public function get_payment_method_data()
    {
        return [
            'id' => $this->name,
            'title' => $this->gateway->get_title(),
            'description' => $this->gateway->get_description(),
            'enabled' => $this->gateway->is_available(),
        ];
    }


}
