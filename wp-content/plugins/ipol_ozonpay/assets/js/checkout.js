let tm = 0, trycnt = 0;
const init = () => {
    trycnt++;
    if (trycnt >= 100) return;
    if ( (typeof(wc) === 'undefined') || (typeof(wp) === 'undefined') || ( typeof(wc.wcSettings) === 'undefined' ) || ( typeof(wc.wcBlocksRegistry) === 'undefined' ) ) setTimeout(init,100);
    else {
        const ozonpayModuleId = 'ozonpay';
        const ozonpaySettings = wc.wcSettings.getSetting(ozonpayModuleId+'_data',{});

        wc.wcBlocksRegistry.registerPaymentMethod({
            name: ozonpayModuleId,
            label: React.createElement(()=>wp.htmlEntities.decodeEntities(ozonpaySettings.title)),
            content: React.createElement(()=>wp.htmlEntities.decodeEntities(ozonpaySettings.description)),
            edit: React.createElement(()=>wp.htmlEntities.decodeEntities(ozonpaySettings.description)),
            canMakePayment: () => ozonpaySettings.enabled,
            ariaLabel: wp.htmlEntities.decodeEntities(ozonpaySettings.title),
            supports: {
                features: ['products'],
            },
        });
    }
}
init();
