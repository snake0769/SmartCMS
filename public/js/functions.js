/**
 * Created by Administrator on 2016/6/16.
 */
//获取QueryString的数组
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
//根据QueryString参数名称获取值
function getQueryStringByName(name){
    var result = location.search.match(new RegExp("[\?\&]" + name+ "=([^\&]+)","i"));
    if(result == null || result.length < 1){
        return "";
    }
    return result[1];
}
//根据QueryString参数索引获取值
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

/* 获取name=item的checked input的id字符串，以','隔开 */
function getCheckedInput(){
    var ids = "";
    $("input[name=item]:checked").each(function(){
        ids += $(this).attr('id') + ",";
    });
    ids = ids.substring(0,ids.length - 1);
    return ids;
}