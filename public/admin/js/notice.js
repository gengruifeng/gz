/**
 * Created by Administrator on 2016/8/4.
 */

function sendmsg() {
    var url = $('#noticeform').attr('action');
    var data =$('#noticeform').serialize();
    pub_alert_confirm(url,data,'发送成功')
}