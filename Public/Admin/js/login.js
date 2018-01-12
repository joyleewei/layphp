layui.use(['form','layer','jquery'],function(){
	var form = layui.form(),
		layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery;
	//登录按钮事件
	form.on("submit(login)",function(elem){
        var url = $(this).attr('data-url')+'?t='+new Date().getTime();
        var $post = elem.field;
        console.log($post);
        $.ajax({
           'type':'POST',
            'dataType':'json',
            'url':url,
            'data':$post,
            'success':function(data){
                if(data.status == 1){
                    layer.msg('登陆成功，正在跳转');
                    window.location.href = '/admin/index/index';
                }else if(data.status == 2){
                    layer.msg(data.msg,{
                        'icon':2,
                        'time':2000
                    });
                    $('#code').click();
                }else{
                    layer.msg(data.msg,{
                        'icon':2,
                        'time':2000
                    });
                }
            },
            'error':function(){
                layer.msg(
                    '发生错误，请稍后重试',{
                        'icon':2,
                        'time':2000
                    }
                );
                //window.location.reload();
            }
        });
        return false;
    });

    $('#code').click(function(){
        var url = '/api/verify/code?t='+new Date().getTime();
        $(this).attr('src',url);
    });
})
