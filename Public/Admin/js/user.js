var $form;
var form;
var $;
layui.config({
	base : "/Public/Admin/js/"
}).use(['form','layer','upload','laydate','jquery'],function(){
	form = layui.form();
	var layer = parent.layer === undefined ? layui.layer : parent.layer;
		$ = layui.jquery;
		$form = $('form');
		laydate = layui.laydate;

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

    //添加验证规则
    form.verify({
        newPwd : function(value, item){
            if(value.length < 6){
                return "密码长度不能小于6位";
            }
        },
        confirmPwd : function(value, item){
            if(!new RegExp($("#oldPwd").val()).test(value)){
                return "两次输入密码不一致，请重新输入！";
            }
        }
    })

    //提交个人资料
    form.on("submit(changeUser)",function(data){
    	var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
    	var $post = data.field;
        delete $post.file;
        $.ajax({
            'url':'/admin/user/info',
            'type':'POST',
            'dataType':'json',
            'data':$post,
            'success':function(data){
                if(data.status == 1){
                    layer.close(index);
                    layer.msg(data.msg,{'icon':1});
                    parent.$(".refresh").click();
                    parent.$('.index_nickname').html(data.nickname);
                    parent.$('.index_image').attr({'src':data.image});
                }else{
                    layer.close(index);
                    layer.msg('接口发生错误，请稍后重试',{'icon':2});
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
    	return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    })

    //修改密码
    form.on("submit(changePwd)",function(data){
    	var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
        setTimeout(function(){
            layer.close(index);
            layer.msg("密码修改成功！");
            $(".pwd").val('');
        },2000);
    	return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    })

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
})