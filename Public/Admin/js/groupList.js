layui.config({
    base : "/Public/Admin/js/"
}).use(['form','layer','jquery'],function(){
    var form = layui.form(),
        layer = parent.layer === undefined ? layui.layer : parent.layer,
        $ = layui.jquery;

    //添加用户组  弹窗
    //改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
    $(window).one("resize",function(){
        $(".groupAdd_btn").click(function(){
            var url = '/admin/auth/group_add.html?t='+new Date().getTime();
            var index = layui.layer.open({
                title : "添加用户组",
                type : 2,
                content : url,
                success : function(layero, index){
                    setTimeout(function(){
                        layui.layer.tips('点击此处返回用户组列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    },500)
                }
            })
            layui.layer.full(index);
        })
    }).resize();
    // 编辑权限 弹窗
    $(window).one("resize",function(){
        $('.groupEdit_btn').click(function(){
            var $id = $(this).attr('data-id');
            var url = '/admin/auth/group_edit.html?t='+new Date().getTime();
            if($id){
                url += '&id='+$id;
            }
            var index = layui.layer.open({
                title : "编辑用户组",
                type : 2,
                content : url,
                success : function(layero, index){
                    setTimeout(function(){
                        layui.layer.tips('点击此处返回用户组列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    },500)
                }
            })
            layui.layer.full(index);
        });
    }).resize();

    // 删除权限 弹窗+ajax
    $("body").on("click",".groupDel_btn",function(){
        var _this = $(this);
        layer.confirm('确定删除此用户组？',{icon:3, title:'提示信息'},function(index){
            var id = _this.attr('data-id');
            var url = '/admin/auth/group_delete.html?t='+new Date().getTime();
            $.ajax({
                'url':url,
                'type':'POST',
                'dataType':'json',
                'data':{'id':id},
                'success':function(data){
                    if(data.status == 1){
                        layer.close(index);
                        layer.msg(data.msg,{'icon':1});
                        parent.$('.refresh').click();
                    }else{
                        layer.close(index);
                        layer.msg(data.msg,{'icon':2});
                    }
                },
                'error':function(XMLHttpRequest, textStatus){
                    var xmlhttp = window.XMLHttpRequest ? new window.XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHttp");
                    xmlhttp.abort();
                    console.log(textStatus);
                    layer.close(index);
                    layer.msg('接口发生错误，请稍后重试',{'icon':2});
                    parent.$('.refresh').click();
                }
            });

        });
        return false;
    });

    // 提交增加用户组
    form.on("submit(addGroup)",function(data){
        //弹出loading
        var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});
        // 发起ajax 请求。
        var url = '/admin/auth/group_add.html?t='+ new Date().getTime();
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
    });

    // 提交编辑用户组
    form.on("submit(editGroup)",function(data){
        //弹出loading
        var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});
        // 发起ajax 请求。
        var url = '/admin/auth/group_edit.html?t='+ new Date().getTime();
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
    });

    // 禁用与启用
    form.on("switch(isShow)",function(data){
        var index = top.layer.msg('状态修改中，请稍候',{icon: 16,time:false,shade:0.8});
        var $id = $(this).attr('data-id');
        if($id){
            var url = '/admin/auth/group_isshow.html?t='+new Date().getTime();
            var $status = 1;
            $.ajax({
                'url':url,
                'dataType':'json',
                'data':{'id':$id,'status':$status},
                'type':'POST',
                'success':function(data){
                    if(data.status == 1){
                        top.layer.close(index);
                        top.layer.msg(data.msg,{'icon':1});
                        form.render('checkbox');
                    }else{
                        top.layer.close(index);
                        top.layer.msg(data.msg,{'icon':2});
                        $(this).prop("checked",!this.checked);
                        form.render('checkbox');
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
            // ajax 请求失败，或者后端返回状态为error ，执行如下操作。ajax 请求成功，不用管状态。
            /*
            $(this).prop("checked",!this.checked);
            form.render();
            */
        }else{
            layer.msg('您好，操作失败，请确认后重试',{icon: 16,time:false,shade:0.8});
        }
    });
});