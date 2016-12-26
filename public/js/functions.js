/**
 * Created by Huzl on 2016/6/16.
 */

/******** 字符串处理 ************/
/**
 * 格式化输出字符串。示例：
 * （1）var str='这是一个测试的字符串：{0} {1}'.format('Hello','world');
 * （2）var str='这是一个测试的字符串：{str0} {str1}'.format({str0:'Hello',str1:'world'});
 * @param args
 * @returns {String}
 */
String.prototype.format = function(args) {
    var result = this;
    if (arguments.length > 0) {
        if (arguments.length == 1 && typeof (args) == "object") {
            for (var key in args) {
                if(args[key]!=undefined){
                    var reg = new RegExp("({" + key + "})", "g");
                    result = result.replace(reg, args[key]);
                }
            }
        }
        else {
            for (var i = 0; i < arguments.length; i++) {
                if (arguments[i] != undefined) {
                    var reg= new RegExp("({)" + i + "(})", "g");
                    result = result.replace(reg, arguments[i]);
                }
            }
        }
    }
    return result;
}


/**
 * 获取QueryString的数组
 * @returns {*}
 */
function getQueryString(){
    var result = location.search.match(new RegExp("[\?\&][^\?\&]+=[^\?\&]+","g"));
    if(result == null){
        return "";
    }
    for(var i = 0; i < result.length; i++){
        result[i] = result[i].substring(1);
    }
    return result;
}
/**
 * 根据QueryString参数名称获取值
 * @param name
 * @returns {*}
 */
function getQueryStringByName(name){
    var result = location.search.match(new RegExp("[\?\&]" + name+ "=([^\&]+)","i"));
    if(result == null || result.length < 1){
        return "";
    }
    return result[1];
}
/**
 * 根据QueryString参数索引获取值
 * @param index
 * @returns {string}
 */
function getQueryStringByIndex(index){
    if(index == null){
        return "";
    }
    var queryStringList = getQueryString();
    if (index >= queryStringList.length){
        return "";
    }
    var result = queryStringList[index];
    var startIndex = result.indexOf("=") + 1;
    result = result.substring(startIndex);
    return result;
}

/**
 * 获取name=item的checked input的id字符串，以','隔开
 * @returns {string}
 */
function getCheckedInputId(){
    var ids = "";
    $("input[name=item]:checked").each(function(){
        ids += $(this).attr('id') + ",";
    });
    ids = ids.substring(0,ids.length - 1);
    return ids;
}

/**
 * 判断请求是否成功
 * @param response
 */
function isSuccessful(response){
    if(response instanceof Object && response.result == 'success'){
        return true;
    }else{
        return false;
    }
}

/**
 * 扩展jquery方法
 */
$.extend({
    /* 在url的尾部添加参数 */
    appendUrlParams: function(url,name,value) {
        var r = url;
        if (r != null && r != 'undefined' && r != "") {
            value = encodeURIComponent(value);
            var reg = new RegExp("(^|)" + name + "=([^&]*)(|$)");
            var tmp = name + "=" + value;
            if (url.match(reg) != null) {
                r = url.replace(eval(reg), tmp);
            }
            else {
                if (url.match("[\?]")) {
                    r = url + "&" + tmp;
                } else {
                    r = url + "?" + tmp;
                }
            }
        }
        return r;
    }
});