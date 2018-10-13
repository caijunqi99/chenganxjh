$(function() {
    var t = getQueryString("store_id");
    $("#goods_search_all").attr("href", WapSiteUrl + "/tmpl/store_goods.html?store_id=" + t);
    $("#search_btn").click(function() {
        var e = $("#search_keyword").val();
        if (e != "") {
            window.location.href = WapSiteUrl + "/tmpl/store_goods.html?store_id=" + t + "&keyword=" + encodeURIComponent(e)
        }
    });
    $.ajax({type: "post", url: ApiUrl + "/Store/store_goods_class.html", data: {store_id: t}, dataType: "json", success: function(t) {
            var e = t.result;
            var o = e.store_info.store_name + " - 店内搜索";
            document.title = o;
            var r = template.render("store_category_tpl", e);
            $("#store_category").html(r)
        }})
});