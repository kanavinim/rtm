<tr valign="top">
    <th scope="row" class="titledesc"></th>
    <td class="forminp">
        <fieldset>
            <button id="ipol_ozonpay_setapiurl" class="button-primary">Установить адрес по умолчанию</button>
        </fieldset>
    </td>
</tr>
<script>
    document.getElementById('ipol_ozonpay_setapiurl').onclick = function(e) {
        e.preventDefault();
        document.getElementById('woocommerce_ozonpay_apiUrl').value = 'https://payapi.ozon.ru';
        return false;
    }
</script>