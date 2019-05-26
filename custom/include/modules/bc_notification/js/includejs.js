function appendScript(url) {
    var head = document.getElementsByTagName("head")[0];
    var theScript = document.createElement("script");
    theScript.type = "text/javascript";
    theScript.src = url;
    //theScript.onreadystatechange = callback;
    theScript.onload = loadOtherJS;
    head.appendChild(theScript);
}
function loadOtherJS(){
    $.getScript("custom/include/modules/bc_notification/js/unserialize.js");
    $.getScript("custom/include/modules/bc_notification/js/notification.js");
}
    if (typeof $ === "undefined") {
        appendScript("custom/include/modules/bc_notification/js/jquery-1.11.0.min.js");
    }else{
        loadOtherJS();
    }

