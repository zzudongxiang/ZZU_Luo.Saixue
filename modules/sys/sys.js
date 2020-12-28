/**
 * 在当前的URL中获取URL中的GET参数
 * @param ParamKey      要获取的GET参数的Key
 * @returns {string}    返回获取到的参数值
 * @constructor         zzudongxiang@163.com
 */
function GetParam(ParamKey) {
    let query = window.location.search.substring(1);
    let vars = query.split("&");
    for (let i = 0; i < vars.length; i++) {
        let pair = vars[i].split("=");
        if (pair[0] === ParamKey) {
            return pair[1];
        }
    }
    return "";
}

/**
 * 设置当前页面的GET参数列表, 但是当前页面并不进行刷新操作
 * @param ParamKey      要变化的GET参数名
 * @param ParamValue    要变化的GET参数值
 * @constructor         zzudongxiang@163.com
 */
function SetParam(ParamKey, ParamValue) {
    window.history.pushState({}, "", UpdateParam(location.href, ParamKey, ParamValue));
}

/**
 * 打开一个弹出窗, 对数据进行修改
 * @param title 弹出框的标题
 * @param url   弹出内容的url
 * @param ids   弹出内容的id参数, 根据此确定是否显示删除按钮
 * @constructor zzudongxiang@163.com
 */
function ShowDialog(title, url, ids) {
    let UpdatePage = false;
    layui.use('layer', function () {
        layui.layer.open({
            type: 2,
            offset: '50px',
            title: title,
            shadeClose: false,
            shade: 0.8,
            area: ['900px', '600px'],
            content: url + (ids > 0 ? ids : (ids < 0 ? ids : '')),
            scrollbar: false,
            resize: false,
            btn: ids > 0 ? ['提交', '删除'] : (ids < 0 ? ['提交'] : []),
            btnAlign: 'c',
            yes: function (index, layero) {
                UpdatePage = true;
                layer.load();
                let submit = layero.find('iframe').contents().find("#btn-yes");
                submit.click();
                return false;
            },
            btn2: function (index, layero) {
                if (confirm("确定要删除吗？")) {
                    let submit = layero.find('iframe').contents().find("#btn-del");
                    UpdatePage = true;
                    layer.load();
                    submit.click();
                }
            },
            end: function (index, layero) {
                if (UpdatePage) {
                    layer.load(0);
                    top.location.href = UpdateParam(location.href, "reload", new Date().getTime());
                }
            },
        });
    });
}

/**
 * 点击按钮, 将页面输入的内容作为Get参数传入, 并刷新页面
 */
function searching() {
    let SearchKey = document.getElementById("search-key");
    if (SearchKey != null) SearchKey = SearchKey.value;
    else SearchKey = "";
    let SearchType = document.getElementById("search-type");
    if (SearchType != null) SearchType = SearchType.value;
    else SearchType = "";
    let SearchUrl = top.location.href;
    SearchUrl = UpdateParam(SearchUrl, "search-key", SearchKey);
    SearchUrl = UpdateParam(SearchUrl, "search-type", SearchType);
    top.location.href = SearchUrl;
}

/**
 * 用于edit窗口提交form表单数据
 * @param opt 提交的方法, 可选: del, update, insert
 */
function process(opt) {
    document.getElementById("method").value = opt;
    document.edit.submit();
}

/**
 * 获取一个链接的GET参数变化之后的新URL字符串
 * @param URL           要改变的URL值
 * @param ParamKey      要改变的GET参数名
 * @param ParamVal      要改变的GET参数值
 * @returns {string}    返回改变之后的新URL
 * @constructor         zzudongxiang@163.com
 */
function UpdateParam(URL, ParamKey, ParamVal) {
    const UrlSplit = URL.split("?");
    let Host = UrlSplit[0] + "?";
    let Params = "";
    if (UrlSplit.length > 1) Params = UrlSplit[1].split("&");
    let Writen = false;
    let i = 0;
    for (i = 0; i < Params.length; i++) {
        if (i !== 0) Host += "&";
        let URL_ParamKey = Params[i].split('=')[0];
        if (URL_ParamKey.toLowerCase() === ParamKey.toLowerCase() && (ParamVal !== "" || ParamVal !== null)) {
            Host += ParamKey + "=" + encodeURIComponent(ParamVal);
            Writen = true;
        } else Host += Params[i];
    }
    if (!Writen) {
        if (i !== 0) Host += "&";
        Host += ParamKey + "=" + encodeURIComponent(ParamVal);
    }
    return Host;
}
