var notificationsWrapper = $('.alert-dropdown');
var notificationsToggle = notificationsWrapper.find('a[data-toggle]');
var notificationsCountElem = notificationsToggle.find('span[data-count]');
var notificationsCount = parseInt(notificationsCountElem.data('count'));
var notifications = notificationsWrapper.find('div.alert-body');

var channel = pusher.subscribe('real-notification');

channel.bind('App\\Events\\RealNotification', function (data) {
    var existingNotifications = notifications.html();
    var newNotificationHtml = '<a class="dropdown-item d-flex align-items-center" href="#">\
                                    <div class="ml-3">\
                                        <div class="icon-circle bg-secondary">\
                                            <i class="far fa-bell text-white"></i>\
                                        </div>\
                                    </div>\
                                    <div>\
                                        <div class="small text-gray-500">'+data.date+'</div>\
                                        <span>تهانينا لقد تم معالجة مقطع الفيديو <b>'+data.videoTitle+'</b> بنجاح</span>\
                                    </div>\
                                </a>';
    notifications.html(newNotificationHtml + existingNotifications);
    notificationsCount += 1;
    notificationsCountElem.attr('data-count', notificationsCount);
    notificationsWrapper.find('.notif-count').text(notificationsCount);
    notificationsWrapper.show();
});