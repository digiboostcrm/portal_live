$(document).ready(function() {
    // At first, let's check if we have permission for notification
    // If not, let's ask for it

    if(("Notification" in window)){
        if (window.Notification && Notification.permission !== "granted") {
            Notification.requestPermission(function(status) {
                if (Notification.permission !== status) {
                    Notification.permission = status;
                } else {
                    setInterval(function(){createNotificationInstance()}, 10000);
                }
            });
        } else {
            setInterval(function(){createNotificationInstance()}, 10000);
        }
    }
});
function createNotificationInstance() {
    if(typeof current_user_id === "undefined"){
        var current_user = "";
    }else{
        var current_user = current_user_id;
    }
    if(current_user != ''){
        if (window.Notification && Notification.permission === "granted") {
            $.ajax({
                url: "index.php?module=bc_Notification&action=getnotify",
                context: document.body,
                success: function(htm) {
                    if (htm != "||") {
                        var splitstr = htm.split("||");
                        if(!jQuery.isEmptyObject(splitstr[0])){
                            var getarrstr = unserialize(splitstr[0]);
                        }
                        for (var singleArray in getarrstr) {
                            var iconImageUrl = splitstr[2];
                            if(getarrstr[singleArray][1] == 'Prospect_Lists'){
                                getarrstr[singleArray][1] = "ProspectLists";
                            }
                            var title = "Hello " + splitstr[1];
                            var subTitle = "New " + getarrstr[singleArray][3] + " Assigned  \n" + getarrstr[singleArray][0];
                            if(navigator.userAgent.match(/(Chrome)/i)){
                                var notification = new Notification(title, {icon: iconImageUrl, body: subTitle});
                            }else{
                                var notification = new Notification(title, {body: subTitle});
                            }
                            notification.addEventListener('click', function() {
                                var target_url = "index.php?module=" + getarrstr[singleArray][1] + "&action=DetailView&record=" + singleArray;
                                window.open(target_url);
                            });
                            if (getarrstr[singleArray][2] == 1) {
                                setTimeout(function() {
                                    notification.close();
                                }, 5000);

                            }
                            $.ajax({
                                url: "index.php?module=bc_Notification&action=updateNotificationRecord&record=" + splitstr[3] ,
                                context: document.body,
                                success:function(){

                                }
                            });

                        }

                    }

                }
            });
        }
    }


}