<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

    <?php @include 'header.php' ?>
<body class="bg-gray-100 text-gray-800 flex flex-col  min-h-screen ">
    <p class="text-center font-bold text-2xl mb-6">
        Order Queue
    </p>
    <div id="tokenqueue" class=" flex flex-row text-2xl font-bold p-6 w-3/5 mr-78 border ml-72 gap-20 border-gray-300 bg-white rounded shadow-md" aria-live="polite">
    </div>
</body>
<!-- for fetching token queue in time -->
<script>
// Function to fetch and update the token queue
setInterval(() => {
    console.log("fetching");

    fetch('fetch_tokens.php') // Your PHP script
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            if (data.status === 'noqueue') {
                // No tokens in the queue, display "noqueue"
                document.getElementById('tokenqueue').innerHTML = 'noqueue';
            } else if (data.status === 'success') {
                // Tokens found, display them
                let tokensHtml = '';
                data.tokens.forEach(function(token) {
                    tokensHtml += '<span>' + token + '</span><br>';
                });
                document.getElementById('tokenqueue').innerHTML = tokensHtml;
            }
        })
        .catch(error => {
            // In case of error, you can display an error message
            document.getElementById('tokenqueue').innerHTML = 'Error fetching tokens.';
            console.error('Error:', error);
        });
}, 1000);
</script>

</html>