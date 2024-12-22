<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      body {
        background-image: url('https://www.publicsectorcatering.co.uk/sites/default/files/styles/single_page/public/images/news/Screenshot%202021-10-08%20084521.png?itok=c7SlOd3e');
        background-size: cover;
        background-position: center;
      }
    </style>
  </head>
  <body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96 opacity-90">
      <div class=" text-center">
        <img src="../images/logo1.png" alt="Logo" class="mx-auto mb-1 w-32 h-32 object-cover" />
      </div>
      
      <h1 class="text-2xl font-bold text-center mb-6">Welcome</h1>
      <?php if (isset($_GET['error'])): ?>
    <p class="text-red-500 text-center mb-4"><?= htmlspecialchars($_GET['error']) ?></p>
<?php endif; ?>

      <form action="loginval.php" method="post">
        <div class="mb-4">
          <label for="logemail" class="block text-gray-700">Email</label>
          <input
            type="email"
            name="logemail"
            id="logemail"
            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Email"
            required
          />
        </div>
        <div class="mb-6">
          <label for="logpass" class="block text-gray-700">Password</label>
          <input
            type="password"
            name="logpass"
            id="logpass"
            class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Password"
            required
          />
        </div>
        <button
          type="submit"
          name="login"
          class="w-full bg-blue-500 text-white py-3 rounded-md hover:bg-blue-600 focus:outline-none"
        >
          Login
        </button>
      </form>
    </div>
  </body>
</html>
