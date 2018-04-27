/**
 * Created by Administrator on 2018/4/27.
 */
//连接socket服务器
var ws = new WebSocket("ws://118.31.20.94:11223");

ws.onopen = function()
{
    // Web Socket 已连接上，使用 send() 方法发送数据
    setTimeout(function(){
        var data = {
            act:'send_t_u',
            data:{
                f:'s_m_t_u',
                recepter:1,
                msg:'自己发送给自己的信息'
            }
        };
        ws.send(JSON.stringify(data))
    },3000)
};

ws.onmessage = function (evt)
{
    var received_msg = evt.data;
    console.log(evt,received_msg);
};

ws.onclose = function()
{
    // 关闭 websocket
    console.log("连接已关闭...");
};