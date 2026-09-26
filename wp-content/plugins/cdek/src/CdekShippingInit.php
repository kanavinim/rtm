<?php

namespace Cdek;

use Cdek\Model\Tariff;
use WP_Http;

class CdekShippingInit
{

    private $cdekDirPath;

    public function __construct($cdekDirPath)
    {
        $this->cdekDirPath = $cdekDirPath;
    }

    public function init () {
        add_action('rest_api_init', [$this, 'cdek_register_route']);
        add_filter('woocommerce_admin_order_data_after_shipping_address', [$this, 'cdek_admin_order_data_after_shipping_address']);
        add_filter('woocommerce_new_order', [$this, 'cdek_woocommerce_new_order_action'], 10, 2);
        add_filter('woocommerce_shipping_methods', [$this, 'add_cdek_shipping_method']);
        add_action('woocommerce_after_shipping_rate', [$this, 'cdek_map_display'], 10, 2);
        add_action('woocommerce_checkout_process', [$this, 'is_pvz_code']);
        add_action('wp_enqueue_scripts', [$this, 'cdek_widget_enqueue_script']);
        add_action('admin_enqueue_scripts', [$this, 'cdek_admin_enqueue_script']);
        add_filter('woocommerce_update_order_review_fragments', [$this, 'cdek_add_update_form_billing'], 99);
        add_filter('woocommerce_checkout_fields', [$this, 'cdek_override_checkout_fields']);
        add_action('wp_footer', [$this, 'cdek_add_script_update_shipping_method']);
    }

    public function cdek_widget_enqueue_script()
    {
        if (is_checkout()) {
            wp_enqueue_style('cdek-css-leaflet', plugin_dir_url($this->cdekDirPath) . 'assets/css/leaflet.css');
            wp_enqueue_script('cdek-css-leaflet-min', plugin_dir_url($this->cdekDirPath) . 'assets/js/lib/leaflet-src.min.js');
            wp_enqueue_style('cdek-css', plugin_dir_url($this->cdekDirPath) . 'assets/css/cdek-map-v1.css');
            wp_enqueue_style('cdek-admin-leaflet-cluster-default', plugin_dir_url($this->cdekDirPath) . 'assets/css/MarkerCluster.Default.min.css');
            wp_enqueue_style('cdek-admin-leaflet-cluster', plugin_dir_url($this->cdekDirPath) . 'assets/css/MarkerCluster.min.css');
            wp_enqueue_script('cdek-admin-leaflet-cluster', plugin_dir_url($this->cdekDirPath) . 'assets/js/lib/leaflet.markercluster-src.min.js');
            wp_enqueue_script('cdek-map', plugin_dir_url($this->cdekDirPath) . 'assets/js/map-v1.js', array('jquery'), '1.7.0', true);
            $this->addYandexMap();
        }
    }

    public function cdek_admin_enqueue_script()
    {
        wp_enqueue_script('cdek-admin-delivery', plugin_dir_url($this->cdekDirPath) . '/../assets/js/delivery-v1.js', array('jquery'), '1.7.0', true);
        wp_enqueue_script('cdek-admin-leaflet', plugin_dir_url($this->cdekDirPath) . '/../assets/js/lib/leaflet-src.min.js');
        wp_enqueue_script('cdek-admin-leaflet-cluster', plugin_dir_url($this->cdekDirPath) . '/../assets/js/lib/leaflet.markercluster-src.min.js');
        wp_enqueue_style('cdek-admin-leaflet', plugin_dir_url($this->cdekDirPath) . '/../assets/css/leaflet.css');
        wp_enqueue_style('cdek-admin-leaflet-cluster-default', plugin_dir_url($this->cdekDirPath) . '/../assets/css/MarkerCluster.Default.min.css');
        wp_enqueue_style('cdek-admin-leaflet-cluster', plugin_dir_url($this->cdekDirPath) . '/../assets/css/MarkerCluster.min.css');
        wp_enqueue_style('cdek-admin-delivery', plugin_dir_url($this->cdekDirPath) . '/../assets/css/delivery-v1.css');
        $this->addYandexMap();
    }

    public function addYandexMap()
    {
        $cdekShippingSettings = Helper::getSettingDataPlugin();
        if (array_key_exists('yandex_map_api_key', $cdekShippingSettings) && $cdekShippingSettings['yandex_map_api_key'] !== '') {
            $WP_Http = new WP_Http();
            $resp = $WP_Http->request('https://api-maps.yandex.ru/2.1?apikey=' . $cdekShippingSettings['yandex_map_api_key'] . '&lang=ru_RU', [
                'method' => 'GET',
                'headers' => [
                    "Content-Type" => "application/json",
                ],
            ]);

            if ($resp['response']['code'] === 200) {
                wp_enqueue_script('cdek-admin-yandex-api', 'https://api-maps.yandex.ru/2.1?apikey=' . $cdekShippingSettings['yandex_map_api_key'] . '&lang=ru_RU');
                wp_enqueue_script('cdek-admin-leaflet-yandex', plugin_dir_url(__FILE__) . 'assets/js/lib/Yandex.js');
            } else {
                $setting = WC()->shipping->load_shipping_methods()['official_cdek'];
                $setting->update_option('yandex_map_api_key', '');
                $setting->update_option('map_layer', '1');
            }


        } else {
            $cdekShippingSettings['map_layer'] = '0';
        }
    }

    public function cdek_register_route()
    {
        register_rest_route('cdek/v1', '/check-auth', [
            'methods' => 'GET',
            'callback' => [$this, 'check_auth'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('cdek/v1', '/get-region', [
            'methods' => 'GET',
            'callback' => [$this, 'get_region'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('cdek/v1', '/get-city-code', [
            'methods' => 'GET',
            'callback' => [$this, 'get_city_code'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('cdek/v1', '/get-pvz', [
            'methods' => 'GET',
            'callback' => [$this, 'get_pvz'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('cdek/v1', '/create-order', [
            'methods' => 'POST',
            'callback' => [$this, 'create_order'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('cdek/v1', '/create-order', [
            'methods' => 'GET',
            'callback' => [$this, 'create_order'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('cdek/v1', '/delete-order', [
            'methods' => 'GET',
            'callback' => [$this, 'delete_order'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('cdek/v1', '/get-waybill', [
            'methods' => 'GET',
            'callback' => [$this, 'get_waybill'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('cdek/v1', '/set-pvz-code-tmp', [
            'methods' => 'GET',
            'callback' => [$this, 'set_pvz_code_tmp'],
            'permission_callback' => '__return_true'
        ]);

    }

    public function create_order($data)
    {
        $api = new CdekApi();

        if (!$api->checkAuth()) {
            return json_encode(['state' => 'error', 'message' => 'Ошибка авторизации. Проверьте идентификатор и секретный ключ клиента в настройках плагина CDEKDelivery']);
        }

        $param = [];
        $orderId = $data->get_param('package_order_id');
        $param = $this->setPackage($data, $orderId, $param);
        $order = wc_get_order($orderId);

        $postOrderData = get_post_meta($orderId, 'order_data');

        $pvzCode = $postOrderData[0]['pvz_code'];
        $tariffId = $postOrderData[0]['tariff_id'];
        $cityCode = $postOrderData[0]['city_code'];
        $cityName = $order->get_shipping_city();
        $cityAddress = $order->get_shipping_address_1();

        if (empty($cityCode)) {
            $cityName = $order->get_shipping_city();
            $stateName = $order->get_shipping_state();
            $cityCode = $api->getCityCodeByCityName($cityName, $stateName);
        }

        if ((int)Tariff::getTariffTypeToByCode($tariffId)) {
            $param['delivery_point'] = $pvzCode;
        } else {
            $param['to_location'] = [
                'code' => $cityCode,
                'city' => $cityName,
                'address' => $cityAddress
            ];
        }

        $param['recipient'] = [
            'name' => $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(),
            'phones' => [
                'number' => $order->get_billing_phone()
            ]
        ];
        $param['tariff_code'] = $tariffId;
        $param['print'] = 'waybill';

        $cdekShippingSettings = Helper::getSettingDataPlugin();
        $services = $cdekShippingSettings['service_list'];

        if ($services !== "") {
            $servicesListForParam = [];
            foreach ($services as $service) {
                if ($service === 'DELIV_RECEIVER' && $tariffId == '62') {
                    $servicesListForParam['code'] = $service;
                }
            }
            $param['services'] = $servicesListForParam;
        }

        $orderDataJson = $api->createOrder($param);
        $orderData = json_decode($orderDataJson);

        if ($orderData->requests[0]->state === 'INVALID') {
            return json_encode(['state' => 'error', 'message' => 'Ошибка. Заказ не создан. (' . $orderData->requests[0]->errors[0]->message . ')']);
        }

        $code = $orderData->entity->uuid;
        $orderInfoJson = $api->getOrder($code);
        $orderInfo = json_decode($orderInfoJson);
        $cdekNumber = null;
        if (property_exists($orderInfo->entity, 'cdek_number')) {
            $cdekNumber = $orderInfo->entity->cdek_number;
        }

        if (empty($cdekNumber)) {
            $cdekNumber = $code;
        }

        $postOrderData[0]['cdek_order_uuid'] = $cdekNumber;
        $postOrderData[0]['cdek_order_waybill'] = $orderData->entity->uuid;
        update_post_meta($orderId, 'order_data', $postOrderData[0]);

        return json_encode(['state' => 'success', 'code' => $cdekNumber, 'waybill' => '/wp-json/cdek/v1/get-waybill?number=' . $orderData->entity->uuid]);
    }

    public function setPackage($data, $orderId, array $param)
    {
        $cdekShippingSettings = Helper::getSettingDataPlugin();
        if ($cdekShippingSettings['has_packages_mode'] === 'yes') {
            $packageData = json_decode($data->get_param('package_data'));
            $param['packages'] = $this->get_packages($orderId, $packageData);
        } else {
            $length = $data->get_param('package_length');
            $width = $data->get_param('package_width');
            $height = $data->get_param('package_height');
            $order = wc_get_order($orderId);
            $items = $order->get_items();
            $itemsData = [];
            $totalWeight = 0;
            foreach ($items as $item) {
                $product = $item->get_product();
                $weight = $product->get_weight();
                $weightClass = new WeightCalc();
                $weight = $weightClass->getWeight($weight);
                $quantity = (int)$item->get_quantity();
                $totalWeight += $quantity * $weight;

                $itemsData[] = [
                    "ware_key" => $product->get_id(),
                    "payment" => ["value" => 0],
                    "name" => $product->get_name(),
                    "cost" => $product->get_price(),
                    "amount" => $item->get_quantity(),
                    "weight" => $weight,
                    "weight_gross" => $weight + 1,
                ];
            }

            $param['packages'] = [
                'number' => $orderId,
                'length' => $length,
                'width' => $width,
                'height' => $height,
                'weight' => $totalWeight,
                'items' => $itemsData
            ];
        }
        return $param;
    }

    public function get_packages($orderId, $packageData)
    {
        $result = [];
        foreach ($packageData as $package) {
            $data = $this->get_package_items($package->items);
            $result[] = [
                'number' => $orderId . '_' . Helper::generateRandomString(5),
                'length' => $package->length,
                'width' => $package->width,
                'height' => $package->height,
                'weight' => $data['weight'],
                'items' => $data['items']
            ];
        }

        return $result;
    }

    public function get_package_items($items)
    {
        $itemsData = [];
        $totalWeight = 0;
        foreach ($items as $item) {
            $product = wc_get_product($item[0]);
            $weight = $product->get_weight();
            $weightClass = new WeightCalc();
            $weight = $weightClass->getWeight($weight);
            $totalWeight += (int)$item[2] * $weight;
            $itemsData[] = [
                "ware_key" => $product->get_id(),
                "payment" => ["value" => 0],
                "name" => $product->get_name(),
                "cost" => $product->get_price(),
                "amount" => $item[2],
                "weight" => $weight,
                "weight_gross" => $weight + 1,
            ];
        }
        return ['items' => $itemsData, 'weight' => $totalWeight];
    }

    public function get_waybill($data)
    {
        $api = new CdekApi();
        $waybillData = $api->createWaybill($data->get_param('number'));
        $waybill = json_decode($waybillData);

        if ($waybill->requests[0]->state === 'INVALID' || property_exists($waybill->requests[0], 'errors')) {
            echo '
        Не удалось создать квитанцию. 
        Для решения проблемы, попробуй пересоздать заказ. Нажмите кнопку "Отменить"
        и введите габариты упаковки повторно.';
            exit();
        }

        $order = json_decode($api->getOrder($data->get_param('number')));
        foreach ($order->related_entities as $entity) {
            if ($entity->uuid === $waybill->entity->uuid) {
                $result = $api->getWaybillByLink($entity->url);
                header("Content-type:application/pdf");
                echo $result;
                exit();
            }
        }

        $result = $api->getWaybillByLink(end($order->related_entities)->url);
        header("Content-type:application/pdf");
        echo $result;
        exit();
    }

    public function check_auth()
    {
        $api = new CdekApi();
        $check = $api->checkAuth();
        if ($check) {
            update_option('cdek_auth_check', '1');
        } else {
            update_option('cdek_auth_check', '0');
        }
        return json_encode(['state' => $check]);
    }

    public function get_region($data)
    {
        $api = new CdekApi();
        return $api->getRegion($data->get_param('city'));
    }

    public function set_pvz_code_tmp($data)
    {
        delete_post_meta(-1, 'pvz_code_tmp');
        $pvzCode = $data->get_param('pvz_code');
        $pvzInfo = $data->get_param('pvz_info');
        $cityCode = $data->get_param('city_code');
        update_post_meta(-1, 'pvz_code_tmp', ['pvz_code' => $pvzCode, 'pvz_info' => $pvzInfo, 'city_code' => $cityCode]);
    }

    public function get_city_code($data)
    {
        $api = new CdekApi();
        return $api->getCityCodeByCityName($data->get_param('city_name'), $data->get_param('state_name'));
    }

    public function get_pvz($data)
    {
        $api = new CdekApi();
        if ($data->get_param('admin')) {
            return $api->getPvz($data->get_param('city_code'), true);
        }
        return $api->getPvz($data->get_param('city_code'));
    }

    public function delete_order($data)
    {
        $api = new CdekApi();
        $orderId = $data->get_param('order_id');
        $postOrderData = get_post_meta($orderId, 'order_data');
        $postOrderData[0]['cdek_order_uuid'] = '';
        update_post_meta($orderId, 'order_data', $postOrderData[0]);

        return $api->deleteOrder($data->get_param('number'));
    }



    public function cdek_map_display($shippingMethodCurrent)
    {
        if (is_checkout() && $this->isTariffTypeFromStore($shippingMethodCurrent)) {
            $cdekShippingSettings = Helper::getSettingDataPlugin();
            $layerMap = $cdekShippingSettings['map_layer'];
            if ($cdekShippingSettings['yandex_map_api_key'] === "") {
                $layerMap = "0";
            }

            $postamat = (int)$this->isPostamatOrStore();

            include plugin_dir_path($this->cdekDirPath) . '/templates/public/open-map.php';
        }
    }

    public function isTariffTypeFromStore($shippingMethodCurrent)
    {
        if ($shippingMethodCurrent->get_method_id() !== 'official_cdek') {
            return false;
        }

        $shippingMethodIdSelected = WC()->session->get('chosen_shipping_methods')[0];

        if ($shippingMethodCurrent->get_id() !== $shippingMethodIdSelected) {
            return false;
        }

        $tariffCode = explode('_', $shippingMethodIdSelected)[2];
        return (bool)(int)Tariff::getTariffTypeToByCode($tariffCode);
    }

    public function isPostamatOrStore()
    {
        $shippingMethodIdSelected = WC()->session->get('chosen_shipping_methods')[0];
        $tariffCode = explode('_', $shippingMethodIdSelected)[2];
        return Tariff::isTariffEndPointPostamatByCode($tariffCode);
    }

    public function cdek_add_update_form_billing($fragments)
    {

        $checkout = WC()->checkout();

        parse_str($_POST['post_data'], $fields_values);

        ob_start();

        echo '<div class="woocommerce-billing-fields__field-wrapper">';

        $fields = $checkout->get_checkout_fields('billing');

        foreach ($fields as $key => $field) {
            $value = $checkout->get_value($key);

            if (!$value && !empty($fields_values[$key])) {
                $value = $fields_values[$key];
            }

            woocommerce_form_field($key, $field, $value);
        }

        echo '</div>';

        $fragments['.woocommerce-billing-fields__field-wrapper'] = ob_get_clean();

        return $fragments;
    }

    public function cdek_override_checkout_fields($fields)
    {
        $chosen_methods = WC()->session->get('chosen_shipping_methods');

        if (!$chosen_methods || $chosen_methods[0] === false) {
            return $fields;
        }

        $output_array = [];
        preg_match('/official_cdek/', $chosen_methods[0], $output_array);

        if (!empty($output_array)) {
            $shippingMethodArray = explode('_', $chosen_methods[0]);
            $tariffCode = $shippingMethodArray[2];
            $tariffType = (int)Tariff::getTariffTypeToByCode($tariffCode);

            if (!isset($fields['billing']['billing_phone'])) {
                $fields['billing']['billing_phone'] = [
                    'label' => 'Телефон',
                    'required' => true,
                    'class' => ['form-row-wide'],
                    'validate' => ['phone'],
                    'autocomplete' => 'tel',
                    'priority' => 100
                ];
            }

            if (!isset($fields['billing']['billing_city'])) {
                $fields['billing']['billing_city'] = [
                    'label' => 'Населённый пункт',
                    'required' => true,
                    'class' => ['form-row-wide', 'address-field'],
                    'autocomplete' => 'address-level2',
                    'priority' => 70
                ];
            }

            if (!isset($fields['billing']['billing_state'])) {
                $fields['billing']['billing_state'] = [
                    'label' => 'Область / район',
                    'required' => true,
                    'class' => ['form-row-wide', 'address-field'],
                    'validate' => ['state'],
                    'autocomplete' => 'address-level1',
                    'priority' => 80,
                    'country_field' => "billing_country",
                    'country' => "RU",
                ];
            }

            if (!$tariffType) {
                if (!isset($fields['billing']['billing_address_1'])) {
                    $fields['billing']['billing_address_1'] = [
                        'label' => 'Адрес',
                        'placeholder' => 'Номер дома и название улицы',
                        'required' => true,
                        'class' => ['form-row-wide', 'address-field'],
                        'autocomplete' => 'address-line1',
                        'priority' => 50
                    ];
                }
            }
        }

        return $fields;
    }

    public function cdek_add_script_update_shipping_method()
    {
        if (is_checkout()) {
            ?>
            <script>
                jQuery(document).on('change', 'input[name="shipping_method[0]"]', function () {
                    jQuery(document.body).trigger('update_checkout')
                });
            </script>
            <?php
        }
    }

    public function is_pvz_code()
    {
        $pvzCode = $_POST['pvz_code'];
        $shippingMethodIdSelected = WC()->session->get('chosen_shipping_methods')[0];
        $tariffCode = $this->getTariffCodeByShippingMethodId($shippingMethodIdSelected);
        if ($this->checkTariffFromStoreByTariffCode($tariffCode)) {
            if (empty($pvzCode)) {
                $pvzCodeTmp = get_post_meta(-1, 'pvz_code_tmp');
                if (empty($pvzCodeTmp[0]['pvz_code'])) {
                    wc_add_notice(__('Не выбран пункт выдачи заказа.'), 'error');
                } else {
                    $_POST['pvz_code'] = $pvzCodeTmp[0]['pvz_code'];
                    $_POST['pvz_address'] = $pvzCodeTmp[0]['pvz_address'];
                    $_POST['city_code'] = $pvzCodeTmp[0]['city_code'];
                    delete_post_meta(-1, 'pvz_code_tmp');
                }
            }
        }
    }

    public function getTariffCodeByShippingMethodId($shippingMethodId)
    {
        return explode('_', $shippingMethodId)[2];
    }

    public function checkTariffFromStoreByTariffCode($tariffCode)
    {
        return (bool)(int)Tariff::getTariffTypeToByCode($tariffCode);
    }

    public function cdek_woocommerce_new_order_action($order_id, $order)
    {
        if ($this->isCdekShippingMethod($order)) {
            $pvzInfo = $_POST['pvz_address'];
            $pvzCode = $_POST['pvz_code'];
            $tariffId = $this->getTariffCodeCdekShippingMethodByOrder($order);
            $cityCode = $_POST['city_code'];

            if ($pvzInfo !== null) {
                $order->set_shipping_address_1($pvzInfo);
                $order->save();
            }

            add_post_meta($order_id, 'order_data', [
                'pvz_address' => $pvzInfo,
                'pvz_code' => $pvzCode,
                'tariff_id' => $tariffId,
                'city_code' => $cityCode
            ]);
        }

    }

    public function add_cdek_shipping_method($methods)
    {
        $methods['official_cdek'] = new CdekShippingMethod();
        return $methods;
    }

    public function cdek_admin_order_data_after_shipping_address($order)
    {
        if ($this->isCdekShippingMethod($order)) {
            $api = new CdekApi();
            if ($api->checkAuth()) {
                $orderId = $order->get_id();
                $postOrderData = get_post_meta($orderId, 'order_data');
                $orderUuid = $postOrderData[0]['cdek_order_uuid'] ?? '';
                $tariffId = $postOrderData[0]['tariff_id'];
                if (!empty($orderUuid) || !empty($tariffId)) {
                    $waybill = $postOrderData[0]['cdek_order_waybill'] ?? '';
                    $items = [];
                    foreach ($order->get_items() as $item) {
                        $items[$item['product_id']] = ['name' => $item['name'], 'quantity' => $item['quantity']];
                    }

                    $cdekShippingSettings = Helper::getSettingDataPlugin();
                    $hasPackages = false;
                    if ($cdekShippingSettings['has_packages_mode'] === 'yes') {
                        $hasPackages = true;
                    }

                    include plugin_dir_path($this->cdekDirPath) . '/templates/admin/create-order.php';
                }
            } else {
                echo 'Авторизация не пройдена. Введите корректные идентификатор и секретный ключ клиента в настройках плагина CDEKDelivery';
            }
        }

    }

    public function getTariffCodeCdekShippingMethodByOrder($order)
    {
        $shippingMethodArray = $order->get_items('shipping');
        $shippingMethod = array_shift($shippingMethodArray);
        return $shippingMethod->get_meta('tariff_code');
    }

    public function isCdekShippingMethod($order)
    {
        $shippingMethodArray = $order->get_items('shipping');
        if (empty($shippingMethodArray)) {
            return false;
        }
        $shippingMethod = array_shift($shippingMethodArray);
        $shippingMethodId = $shippingMethod->get_method_id();
        if ($shippingMethodId === 'official_cdek') {
            return true;
        }
        return false;
    }

}