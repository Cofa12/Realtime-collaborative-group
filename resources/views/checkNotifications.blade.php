<!DOCTYPE html>
<head>
    <title>Pusher Test</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>

//        // Enable pusher logging - don't include this in production
//        Pusher.logToConsole = true;
//
//        var pusher = new Pusher('426c0cb69558a59af063', {
//            cluster: 'eu'
//        });
//
//        var channel = pusher.subscribe('my-channel1');
//        channel.bind('my-event', function(data) {
//            alert(JSON.stringify(data));
//        });


    </script>
</head>
<body>
<h1>Pusher Test</h1>
<p>
    <textarea id="myTextarea" rows="5" cols="50"></textarea>
</p>

<script>

    // Initialize Pusher
    // const pusher = new Pusher('426c0cb69558a59af063', {
    //     cluster: 'eu',
    //     auth: {
    //         headers: {
    //             Authorization: 'Bearer ' + '1|UoedNiuhKeueXbrxdjdSFERN6GMmfL9TeFo6qRo293f7a288'
    //         }
    //     }
    // });
    //
    // // Create a Pusher channel
    // const channel = pusher.subscribe('private-textArea.typing');
    //
    //
    // const channel = pusher.subscribe('textArea.typing');
    //
    // // Check for successful subscription
    // channel.bind('pusher:subscription_succeeded', () => {
    //     console.log('Successfully subscribed to private-textArea.typing');
    //
    //     // Trigger event when typing in the textarea
    //     document.getElementById('myTextarea').addEventListener('input', function() {
    //         channel.trigger('client-text-input', {
    //             message: this.value
    //         });
    //     });
    // });


    // document.addEventListener('DOMContentLoaded', function() {
    //     // Get the textarea element by its ID
        const textarea = document.getElementById('myTextarea');
    //     //
    //     // document.getElementById('myTextarea').addEventListener('input', function() {
    //     //     Echo.private('ptextArea.typing')
    //     //         .whisper('client-text-input', {
    //     //             message: this.value
    //     //         });
    //     // });
    //     // Add an event listener for the 'input' event
        textarea.addEventListener('input', function() {
            // Send the current value of the textarea to the Pusher channel
            // channel.trigger('client-text-input', {
            //     message: textarea.value
            // });
            const token = '1|UoedNiuhKeueXbrxdjdSFERN6GMmfL9TeFo6qRo293f7a288';
            const url = 'http://127.0.0.1:8000/api/userTyping'; // Replace with your actual endpoint
            const data = {
                text: textarea.value,
            };

            fetch(url, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`, // Attach the Bearer token
                    'Content-Type': 'application/json' // Specify JSON content type
                },
                body: JSON.stringify(data) // Include data payload
            })
                // .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            console.log(textarea.value)
        });

    // const textarea = document.getElementById('myTextarea');
    //
    // // Add an event listener for the 'input' event
    // textarea.addEventListener('input', function() {
    //     // Log the current value of the textarea to the console
    //     console.log(textarea.value);
    // });
</script>
</body>
