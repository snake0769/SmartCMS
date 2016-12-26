/*********************************** Layer ************************************/
/******************************************************************************/
var defLayerWidth = 800;
var defLayerHeight = 500;


/******************************** DataTables **********************************/
/******************************************************************************/
/**
 * 获取查询用的排序参数
 * @param data datatables请求时自带的参数
 * @param map 字段映射表
 */
function getOrderParams(data,map){
    var order = [];
    if(data.order){
        for(var i=0;i<data.order.length;i++){
            var col = map[data.order[i].column];
            if(typeof(col) == 'object'){
                col = col.name;
            }

            if(col != null){
                order.push({
                    column:map[data.order[i].column],
                    dir:data.order[i].dir
                });
            }
        }
    }
    return JSON.stringify(order);
}

/**
 * 获取顺序字段组，返回结果直接用于初始化dataTables的columns属性
 * @param cols 示例：['id','name',{name:'active',render:function(data,type,row){...return...}}]，有顺序要求
 * @returns {Array}
 */
function getColumnsSet(cols){
    var colSet = [];
    for(var i=0;i<cols.length;i++){
        if(typeof(cols[i]) == 'string' ){
            colSet.push({
                data:cols[i],
                name:cols[i],
                orderable:true
            });
        }else if(typeof(cols[i]) == 'object'){
            colSet.push({
                data:cols[i].name,
                name:cols[i].name,
                render:cols[i].render,
                className:cols[i].className,
                orderable:cols[i].orderable ? true: false
            });
        }
    }
    return colSet;
}

/** 输出序号的表格回执回调函数 **/
var drawSequence = function(){
    var api = this.api();
    var startIndex= api.context[0]._iDisplayStart;//获取到本页开始的条数
    api.column(1).nodes().each(function(cell, i) {
        cell.innerHTML = startIndex + i + 1;
    });
};

/** 添加行tr的class，用于显示样式展示**/
var addRowClass = function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
    $(nRow).addClass('text-c');
};

/** 设置表格数据"处理中"的区块显示样式 **/
var processingStyle = function(e, settings, processing){
    var imgUrl = ASSET_URL + '/js/backend/lib/layer/2.1/skin/default/loading-0.gif';
    var html = '<div class="dataTable_loading"><img src="{0}"/></div>'.format(imgUrl);
    $('#dataTable_processing').html(html);
};

/** 表格各元素的位置排放顺序 **/
var elementPosition = 'trip';