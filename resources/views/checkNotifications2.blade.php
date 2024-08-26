<!DOCTYPE html>
<head>
    <title>Pusher Test</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

</head>
<body>
<h1>Pusher Test</h1>
<p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.

    <textarea id="myTextarea" rows="5" cols="50"></textarea>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('426c0cb69558a59af063', {
            cluster: 'eu'
        });

        const textarea = document.getElementById('myTextarea');
        var channel = pusher.subscribe('User-typing');

            channel.bind('typing-Event', function(data) {
                // Display the received message in the <span> with id="output"
                textarea.innerText = data.message;
                console.log('Received message:', data.message);

        });

    </script>
</p>
</body>
