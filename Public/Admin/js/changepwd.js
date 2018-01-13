var $form;
var form;
var $;
layui.config({
    base : "/Public/Admin/js/"
}).use(['form','layer','jquery'],function(){
    form = layui.form();
    var layer = parent.layer === undefined ? layui.layer : parent.layer;
    $ = layui.jquery;
    $form = $('form');

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

    //修改密码
    form.on("submit(changePwd)",function(data){
        var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
        var $post = data.field;
        var url = '/admin/user/changepwd.html?t='+new Date().getTime();
        $.ajax({
            'url':url,
            'type':'POST',
            'dataType':'json',
            'data':$post,
            'success':function(data){
                if(data.status == 1){
                    layer.close(index);
                    layer.msg(data.msg,{'icon':1});
                    parent.$(".refresh").click();
                }else{
                    layer.close(index);
                    layer.msg(data.msg,{'icon':2});
                }
            },
            'error':function(XMLHttpRequest, textStatus){
                var xmlhttp = window.XMLHttpRequest ? new window.XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHttp");
                xmlhttp.abort();
                console.log(textStatus);
                layer.msg('接口发生错误，请稍后重试',{'icon':2});
            }
        })
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    })
})