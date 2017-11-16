function is_kingkr_obj() {
    if (typeof local_kingkr_obj == 'undefined') {
        return false
    } else {
        return true
    }
}
function judge() {
    var u = navigator.userAgent;
    return {
        ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
        android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1,
    }
}
function share(content, imageurl, targetUrl, title) {
    var share = "{\"content\":\"" + content + "\",\"imageurl\":\"" + imageurl + "\",\"targetUrl\":\"" + targetUrl + "\",\"title\":\"" + title + "\"}";
    javascript: local_kingkr_obj.share(share)
}
function loginResult(r) {}
function payResult(r) {}
function openDownLoad() {
    javascript: local_kingkr_obj.openDownLoadFile()
}
function login(platform, forwardurl, callbackMethod) {
    var login = "{\"platform\":\"" + platform + "\",\"forwardurl\":\"" + forwardurl + "\",\"callbackMethod\":\"" + callbackMethod + "\"}";
    javascript: local_kingkr_obj.login(login)
}
function pay(paytype, callbackMethod, partner, seller_id, out_trade_no, subject, bodys, total_fee, modify_url, service, payment_type, charset, it_b_pay, key, sign, sign_type) {
    payment_type = payment_type ? payment_type: "1";
    charset = charset ? charset: 'utf-8';
    it_b_pay = it_b_pay ? it_b_pay: "30m";
    sign_type = sign_type ? sign_type: "RSA";
    var order = "partner=" + partner + "&seller_id=" + seller_id + "&out_trade_no=" + out_trade_no + "&subject=" + subject + "&body=" + bodys + "&total_fee=" + total_fee + "&modify_url=" + modify_url + "&service=" + service + "&payment_type=" + payment_type + "&_input_charset=" + charset + "&it_b_pay=" + it_b_pay + "&key=" + key + "&sign=" + sign + "&sign_type=" + sign_type;
    var payType = '{"paytype":' + paytype + ',"callbackMethod":' + callbackMethod + '}';
    javascript: local_kingkr_obj.payType(order, payType)
}
function audioPlay(operator) {
    var data = "{\"operator\":" + operator + "}";
    javascript: local_kingkr_obj.audioPlay(data)
}
function qrcoder() {
    javascript: local_kingkr_obj.qrcoder()
}
function cleancache() {
    type = judge();
    if (type.ios == true) {
        var message = {
            'methodName': 'cleancache'
        };
        window.webkit.messageHandlers.local_kingkr_obj.postMessage(message)
    } else {
        local_kingkr_obj.cleanCache()
    }
}
function qrcode() {
    javascript: local_kingkr_obj.qrcoderWithCallback()
}
function qrcodeCallback(result) {}
function audioPlay(data) {
    javascript: local_kingkr_obj.audioPlay(data)
}