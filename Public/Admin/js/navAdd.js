layui.config({
    base : "/Public/Admin/js/"
}).use(['form','layer','jquery'],function(){
    var form = layui.form(),
        layer = parent.layer === undefined ? layui.layer : parent.layer,
        $ = layui.jquery;

    form.on("submit(addNav)",function(data){
        //弹出loading
        var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});
        // 发起ajax 请求。
        var url = '/admin/nav/add.html?t='+ new Date().getTime();
        var $post = data.field;
        $.ajax({
            'url':url,
            'type':'POST',
            'dataType':'json',
            'data':$post,
            'success':function(data){
                if(data.status == 1){
                    top.layer.close(index);
                    top.layer.msg(data.msg,{'icon':1});
                    layer.closeAll("iframe");
                    parent.location.reload();
                }else{
                    top.layer.close(index);
                    top.layer.msg(data.msg,{'icon':2});
                }
            },
            'error':function(XMLHttpRequest, textStatus){
                var xmlhttp = window.XMLHttpRequest ? new window.XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHttp");
                xmlhttp.abort();
                console.log(textStatus);
                top.layer.close(index);
                top.layer.msg('接口发生错误，请稍后重试',{'icon':2});
                layer.closeAll("iframe");
                parent.location.reload();
            }
        });
        return false;
    })

    form.on("submit(editNav)",function(data){
        //弹出loading
        var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});
        // 发起ajax 请求。
        var url = '/admin/nav/edit.html?t='+ new Date().getTime();
        var $post = data.field;
        $.ajax({
            'url':url,
            'type':'POST',
            'dataType':'json',
            'data':$post,
            'success':function(data){
                if(data.status == 1){
                    top.layer.close(index);
                    top.layer.msg(data.msg,{'icon':1});
                    layer.closeAll("iframe");
                    parent.location.reload();
                }else{
                    top.layer.close(index);
                    top.layer.msg(data.msg,{'icon':2});
                }
            },
            'error':function(XMLHttpRequest, textStatus){
                var xmlhttp = window.XMLHttpRequest ? new window.XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHttp");
                xmlhttp.abort();
                console.log(textStatus);
                top.layer.close(index);
                top.layer.msg('接口发生错误，请稍后重试',{'icon':2});
                layer.closeAll("iframe");
                parent.location.reload();
            }
        });
        return false;
    })

})
