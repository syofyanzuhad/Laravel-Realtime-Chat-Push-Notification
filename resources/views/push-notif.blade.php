<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Demo Application</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap-notifications@1.0.3/dist/stylesheets/bootstrap-notifications.min.css">
   <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
   <![endif]-->
</head>
<body>
   <nav class="navbar navbar-inverse">
      <div class="container-fluid">
      <div class="navbar-header">
         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-9" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
         <a class="navbar-brand" href="#">Demo App</a>
      </div>

      <div class="collapse navbar-collapse">
         <ul class="nav navbar-nav">
            <li class="dropdown dropdown-notifications">
            <a href="#notifications-panel" class="dropdown-toggle" data-toggle="dropdown">
               <i data-count="0" class="glyphicon glyphicon-bell notification-icon"></i>
            </a>

            <div class="dropdown-container">
               <div class="dropdown-toolbar">
                  <div class="dropdown-toolbar-actions">
                  <a href="#">Mark all as read</a>
                  </div>
                  <h3 class="dropdown-toolbar-title">Notifications (<span class="notif-count">0</span>)</h3>
               </div>
               <ul class="dropdown-menu">
               </ul>
               <div class="dropdown-footer text-center">
                  <a href="#">View All</a>
               </div>
            </div>
            </li>
            <li><a href="#">Timeline</a></li>
            <li><a href="#">Friends</a></li>
         </ul>
      </div>
      </div>
   </nav>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
   <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/notifyjs@3.0.0/dist/notify.min.js"></script>

   <script type="text/javascript">
      var notificationsWrapper   = $('.dropdown-notifications');
      var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
      var notificationsCountElem = notificationsToggle.find('i[data-count]');
      var notificationsCount     = parseInt(notificationsCountElem.data('count'));
      var notifications          = notificationsWrapper.find('ul.dropdown-menu');

      // if (notificationsCount <= 0) {
      // notificationsWrapper.hide();
      // }

      // Enable pusher logging - don't include this in production
      // Pusher.logToConsole = true;

      var pusher = new Pusher('6b31c0b816157919e48b', {
         encrypted: true,
         cluster: "ap1",
      });

      // Pusher.logToConsole = true;

      // Subscribe to the channel we specified in our Laravel Event
      var channel = pusher.subscribe('push-notification');

      function onPermissionGranted () {
         console.log('Permission has been granted by the user');
      }
      
      function onPermissionDenied () {
         console.warn('Permission has been denied by the user');
      }
      
      if (Notification.permission !== "granted") {
         if (Notify.isSupported()) {
            Notify.requestPermission(onPermissionGranted, onPermissionDenied);
         }
      }

      // Bind a function to a Event (the full Laravel class)
      channel.bind('App\\Events\\PushNotification', function(data) {
         var existingNotifications = notifications.html();
         var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
         var newNotificationHtml = `
            <li class="notification active">
               <div class="media">
                  <div class="media-left">
                     <div class="media-object">
                     <img src="https://api.adorable.io/avatars/71/`+avatar+`.png" class="img-circle" alt="50x50" style="width: 50px; height: 50px;">
                     </div>
                  </div>
                  <div class="media-body">
                     <strong class="notification-title">`+data.message+`</strong>
                     <!--p class="notification-desc">Extra description can go here</p-->
                     <div class="notification-meta">
                     <small class="timestamp">about a minute ago</small>
                     </div>
                  </div>
               </div>
            </li>
         `;
         notifications.html(newNotificationHtml + existingNotifications);

         doNotification()
         function onShowNotification () {
            console.log('notification is shown!');
         }
         
         function onCloseNotification () {
            console.log('notification is closed!');
         }
         
         function onClickNotification () {
            console.log('notification was clicked!');
         }
         
         function onErrorNotification () {
            console.error('Error showing notification. You may need to request permission.');
         }
         
         function doNotification () {

            var myNotification = new Notify("Assalamu'alaikum", {
                  body: data.message,
                  tag: Date.now(),
                  notifyShow: onShowNotification,
                  notifyClose: onCloseNotification,
                  notifyClick: onClickNotification,
                  notifyError: onErrorNotification,
                  timeout: 10000,
                  renotify:true
            });
         
            myNotification.show();
         }

         notificationsCount += 1;
         notificationsCountElem.attr('data-count', notificationsCount);
         notificationsWrapper.find('.notif-count').text(notificationsCount);
         notificationsWrapper.show();
      });
   </script>
</body>
</html>