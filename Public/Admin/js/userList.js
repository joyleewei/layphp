layui.config({
    base : "/Public/Admin/js/"
}).use(['form','layer','upload','laydate','jquery'],function(){
    var form = layui.form();
    var layer = parent.layer === undefined ? layui.layer : parent.layer;
    var $ = layui.jquery;
    var laydate = layui.laydate;
    $form = $('form');

    //添加后台管理员 弹窗
    $(".userAdd_btn").click(function(){
        var $url = '/admin/auth/user_add.html?t='+new Date().getTime();
        var index = layui.layer.open({
            title : "添加后台管理员",
            type : 2,
            content : $url,
            success : function(layero, index){
                setTimeout(function(){
                    layui.layer.tips('点击此处返回管理员列表', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                },500)
            }
        });
        //改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
        $(window).resize(function(){
            layui.layer.full(index);
        });
        layui.layer.full(index);
    });

    // 提交 增加后台管理员资料 post
    form.on("submit(addUser)",function(data){
        var index = top.layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
        var $post = data.field;
        delete $post.file;
        var $url = '/admin/auth/user_add.html?t='+new Date().getTime();
        $.ajax({
            'url':$url,
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
                    layer.closeAll("iframe");
                    parent.location.reload();
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
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    });

    //编辑用户 弹窗
    $('.userEdit_btn').click(function(){
        var $id = $(this).attr('data-id');
        var $url = '/admin/auth/user_edit.html?t='+new Date().getTime()+'&id='+$id;
        var index = layui.layer.open({
            title : "编辑后台管理员",
            type : 2,
            content : $url,
            success : function(layero, index){
                setTimeout(function(){
                    layui.layer.tips('点击此处返回管理员列表', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                },500)
            }
        });
        //改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
        $(window).resize(function(){
            layui.layer.full(index);
        });
        layui.layer.full(index);
    });

    form.on('submit(editUser)',function(data){
        var index = top.layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
        var $post = data.field;
        delete $post.file;
        var $url = '/admin/auth/user_edit.html?t='+new Date().getTime();
        $.ajax({
            'url':$url,
            'type':'POST',
            'dataType':'json',
            'data':$post,
            'success':function(data){
                if(data.status == 1){
                    top.layer.close(index);
                    top.layer.msg(data.msg,{'icon':1});
                    layer.closeAll("iframe");
                    parent.location.reload();
                    if(data.image){
                        parent.parent.$('.index_nickname').html(data.nickname);
                        parent.parent.$('.index_image').attr({'src':data.image});
                    }
                }else{
                    top.layer.close(index);
                    top.layer.msg(data.msg,{'icon':2});
                    layer.closeAll("iframe");
                    parent.location.reload();
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
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    });

    // 查看管理员信息 弹窗
    $('.userView_btn').click(function(){
        var $id = $(this).attr('data-id');
        var $url = '/admin/auth/user_view.html?t='+new Date().getTime()+'&id='+$id;
        var index = layui.layer.open({
            title : "查看后台管理员",
            type : 2,
            content : $url,
            success : function(layero, index){
                setTimeout(function(){
                    layui.layer.tips('点击此处返回管理员列表', '.layui-layer-setwin .layui-layer-close', {
                        tips: 3
                    });
                },500)
            }
        });
        //改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
        $(window).resize(function(){
            layui.layer.full(index);
        });
        layui.layer.full(index);
    });

    // 删除后台用户
    $("body").on("click",".userDel_btn",function(){  //删除
        var _this = $(this);
        layer.confirm('确定删除此后台管理员？',{icon:3, title:'提示信息'},function(index){
            var $id = _this.attr('data-id');
            var $url = '/admin/auth/user_delete.html?t='+new Date().getTime();
            $.ajax({
               'url':$url,
                'type':'POST',
                'dataType':'json',
                'data':{'id':$id},
                'success':function(data){
                    if(data.status == 1){
                        layer.close(index);
                        layer.msg(data.msg,{'icon':1});
                        parent.location.reload();
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
                }
            });
        });
    });

    //批量删除后台用户
    $(".batchDel").click(function(){
        var $checked = $('.user_list tbody input[type="checkbox"][name="checked"]:checked');
        var $len = $checked.length;
        if($len >0){
            layer.confirm('确定删除选中的后台管理员？',{icon:3, title:'提示信息'},function(index){
                var index = layer.msg('删除中，请稍候',{icon: 16,time:false,shade:0.8});
                // 发起ajax请求
                var $json = '{"id":"';
                for(var i=0;i<$len;i++){
                    $id = $checked.eq(i).attr('data-id');
                    if(i == $len -1){
                        $json += $id+'"';
                    }else{
                        $json += $id +',';
                    }
                }
                $json +='}';
                var $post = JSON.parse($json);
                var $url = '/admin/auth/user_delete.html?t='+new Date().getTime();
                $.ajax({
                    'url':$url,
                    'type':'POST',
                    'dataType':'json',
                    'data':{'id':$id},
                    'success':function(data){
                        if(data.status == 1){
                            layer.close(index);
                            layer.msg(data.msg,{'icon':1});
                            parent.location.reload();
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
                    }
                });
                return false;
            })
        }else{
            layer.msg("请选择需要删除的后台管理员");
        }

    });

    // 图片上传
    layui.upload({
        'url' : "/api/upload/image.html?t="+new Date().getTime(),
        'method':'POST',
        'ext': 'jpg|png|gif|jpeg',
        'success': function(data){
            if(data.status == 1){
                $('#userFace').attr('src',data.path);
                $('#image').val(data.path);
                layer.msg(data.msg);
            }else{
                layer.msg(data.msg,{'icon':2,'time':2000});
                // 刷新当前页面
                $(".refresh").click();
            }
        }
    });
    // 省 市 县 三级联动
    form.on('select(province)', function(data) {
        // 县/区先清零
        $form.find('select[name=area]').html('<option value="">请选择县/区</option>').attr({'disabled':'disabled'});
        var value = data.value;
        loadCity(value);
        return false;
    });
    function loadCity(id){
        var $str = '<option value="">请选择市</option>';
        $form.find('select[name=city]').html($str);
        var url = '/api/city/get.html?t='+new Date().getTime();
        $.ajax({
            'url':url,
            'type':'POST',
            'dataType':'json',
            'data':{'areaid':id},
            'success':function(data){
                if(data.status == 1){
                    if(data.count > 0){
                        var cityHtml = '<option value="">请选择市</option>';
                        $.each(data.area,function(index,elem){
                            cityHtml += '<option value="'+index+'">'+elem+'</option>';
                        });
                        $form.find('select[name=city]').html(cityHtml);
                        form.render('select');
                        form.on('select(city)', function(data) {
                            var value = data.value;
                            loadArea(value);
                            return false;
                        });
                    }else{
                        $form.find('select[name=city]').attr({'disabled':'disabled'});
                    }
                }else{
                    layer.msg('接口发生错误，请稍后重试',{'icon':2});
                }
            },
            'error':function(XMLHttpRequest, textStatus){
                var xmlhttp = window.XMLHttpRequest ? new window.XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHttp");
                xmlhttp.abort();
                console.log(textStatus);
                layer.msg('接口发生错误，请稍后重试',{'icon':2});
            }
        });
        return false;
    }
    function loadArea(id){
        var $str = '<option value="">请选择县/区</option>';
        var url = '/api/city/get.html?t='+new Date().getTime();
        $form.find('select[name=area]').html($str).removeAttr('disabled');
        $.ajax({
            'url':url,
            'type':'POST',
            'dataType':'json',
            'data':{'areaid':id},
            'success':function(data){
                if(data.status==1){
                    if(data.count > 0){
                        var areaHtml = '<option value="">请选择县/区</option>';
                        $.each(data.area,function(index,elem){
                            areaHtml += '<option value="'+index+'">'+elem+'</option>';
                        })
                        $form.find('select[name=area]').html(areaHtml);
                        form.render('select');
                    }else{
                        $form.find('select[name=area]').attr({'disabled':'disabled'});
                    }
                }
            },
            'error':function(XMLHttpRequest, textStatus){
                var xmlhttp = window.XMLHttpRequest ? new window.XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHttp");
                xmlhttp.abort();
                console.log(textStatus);
                layer.msg('接口发生错误，请稍后重试',{'icon':2});
            }
        });
        return false;
    }
    //全选
    form.on('checkbox(allChoose)', function(data){
        var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]');
        child.each(function(index, item){
            item.checked = data.elem.checked;
        });
        form.render('checkbox');
    });

    //通过判断栏目是否全部选中来确定全选按钮是否选中
    form.on("checkbox(choose)",function(data){
        var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]');
        var childChecked = $(data.elem).parents('table').find('tbody input[type="checkbox"]:checked')
        if(childChecked.length == child.length){
            $(data.elem).parents('table').find('thead input#allChoose').get(0).checked = true;
        }else{
            $(data.elem).parents('table').find('thead input#allChoose').get(0).checked = false;
        }
        form.render('checkbox');
    });
});