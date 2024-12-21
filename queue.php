<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div id="tokenqueue">

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