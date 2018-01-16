layui.config({
    base : "/Public/Admin/js/"
}).use(['form','layer','jquery'],function(){
    var form = layui.form(),
        layer = parent.layer === undefined ? layui.layer : parent.layer,
        $ = layui.jquery;

    //添加菜单 添加子菜单 弹窗
    //改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
    $(window).one("resize",function(){
        $(".navAdd_btn").click(function(){
            var $id = $(this).attr('data-id');
            var url = '/admin/nav/add.html?t='+new Date().getTime();
            if($id){
                url += '&pid='+$id;
            }
            var index = layui.layer.open({
                title : "添加菜单",
                type : 2,
                content : url,
                success : function(layero, index){
                    setTimeout(function(){
                        layui.layer.tips('点击此处返回文章列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    },500)
                }
            })
            layui.layer.full(index);
        })
    }).resize();
    // 编辑菜单 弹窗
    $(window).one("resize",function(){
        $('.navEdit_btn').click(function(){
            var $id = $(this).attr('data-id');
            var url = '/admin/nav/edit.html?t='+new Date().getTime();
            if($id){
                url += '&id='+$id;
            }
            var index = layui.layer.open({
                title : "编辑菜单",
                type : 2,
                content : url,
                success : function(layero, index){
                    setTimeout(function(){
                        layui.layer.tips('点击此处返回文章列表', '.layui-layer-setwin .layui-layer-close', {
                            tips: 3
                        });
                    },500)
                }
            })
            layui.layer.full(index);
        });
    }).resize();
    // 删除菜单 弹窗 ajax
    $("body").on("click",".navDel_btn",function(){
        var _this = $(this);
        layer.confirm('确定删除此菜单？',{icon:3, title:'提示信息'},function(index){
            var id = _this.attr('data-id');
            console.log(id);
            var url = '/admin/nav/delete.html?t='+new Date().getTime();
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
    })
    // 更新排序
    $('.navsort_btn').click(function(){
        var index = top.layer.msg('数据提交中，请稍候',{icon: 16,time:false,shade:0.8});
        var $sort = $('.layui-sort');
        var $length = $sort.length;
        var $json = "{";
        var url = '/admin/nav/sort.html?t='+new Date().getTime();
        for(var i=0;i<$length;i++){
            $id = $sort.eq(i).attr("data-id");
            $value = $sort.eq(i).val();
            if(!$value){
                $value = 1;
            }
            if(i == $length-1){
                $json += '"'+$id +'":'+$value;
            }else{
                $json += '"'+$id +'":'+$value+',';
            }
        }
        $json += "}";
        var $post = JSON.parse($json);

        $.ajax({
            'url':url,
            'dataType':'json',
            'type':'POST',
            'data':$post,
            'success':function(data){
                if(data.status == 1){
                    top.layer.close(index);
                    top.layer.msg(data.msg,{'icon':1});
                    parent.$('.refresh').click();
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
                parent.$('.refresh').click();
            }
        });
        return false;
    });
    // 增加菜单提交
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
    // 编辑菜单提交
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