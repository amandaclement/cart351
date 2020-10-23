<?php
//check if there has been something posted to the server to be processed
if($_SERVER['REQUEST_METHOD'] == 'GET')
{
// need to process
// echo("inside get request<br>");
$request = $_SERVER['REQUEST_URI']; // THE WHOLE URL (with queries)
// echo("the request: ".$request);

// if the form was submitted ...
if($_GET['beverage_type']){
  // assign the varibales
  $type = $_GET['beverage_type'];
  $cups = $_GET['beverage_cups'];
  $ingredient = $_GET['beverage_ingredient'];
  $size = $_GET['beverage_size'];

  // Read JSON file into an array to append our new object
  // and then write back the contents to the file

  // Using an associative array to structure our chape data
  $newBeverageArray = array(
    'type' => $type,
    'cups' => $cups,
    'ingredient' => $ingredient,
    'size' => $size
  );
  // open or read JSON data
  $data_results = file_get_contents('beverages.json');
  // put into an array (DECODE)
  $tempArray = json_decode($data_results);
  // save in JSON format (ENCODE)
  file_put_contents('beverages.json', $jsonData);
  // resend the headers and reload the page (to clear the GET request)
  //header("Location: /inputToGet.php");
  // now, everytime data is submitted and the page reloads and reads from JSON file,
  // we have a new receipt displayed
  //append additional array to json file
  $tempArray[]=$newBeverageArray;
  //save in JSON format (ENCODE)
  $jsonData = json_encode($tempArray);
  //save to the file
  file_put_contents('beverages.json', $jsonData);
  // ***resend the headers and reload the page (to clear the GET request)
  header("Location: index.php");
}
}
?>
<html>

<head>
  <title> EXERCISE 3 - CART 351 2020 </title>
  <meta charset="UTF-8">
  <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <script>
  $(document).ready(function(){
      $.getJSON('beverages.json', function(data) {
        // displaying the data on the screen
        let displayInfo = $("<div>"); // create a div to display JSON info
        $(displayInfo).appendTo("#showData"); // append it to showData (HTML div)
        let displayPrice = $("<div>"); // create a div to display price
        $(displayPrice).css("font-weight","bold").css("padding-bottom","15px").appendTo("#showData"); // append it to showData (HTML div)

        // generating a random price each time
        let price = Math.floor(Math.random() * 10); // returns a random integer from 0 to 9
        let tax = Math.floor(Math.random() * 100); // returns a different random integer from 0 to 99
        displayPrice.html("Your total is $" + price + '.' + tax);

        // when user first clicks button to search using order number ...
        $("#getData").click(function(event) {
          let inputData = $("#searchText").val(); // get the user's search input
          console.log("user typed: " + inputData); // log it

          // if the user enters a number outside of the scope, alert them
          if (inputData > data.length || inputData < 1) {
            alert("Please enter a number between 0 and " + (data.length + 1) + ".");
          }

          // parse through the JSON data (index and associated values)
           $.each(data, function(index, value) {
             if (parseInt(inputData) === (index + 1)) {
               // display the receipt
               $("#receiptInfo").show();
               // and display the appropriate info
                displayInfo.html("Beverage: " + value.type + "<br>" + "Number of Cups: " + value.cups + "<br>" + "Added Ingredient(s): " + value.ingredient + "<br>" + "Size: " + value.size);
             }
          });
        });

        // when user clicks button after inputting their own info ...
        $("#buttonSearch").click(function(event) {
          // parse through the JSON data (index and associated values)
           $.each(data, function(index, value) {
             // display the receipt
             $("#receiptInfo").show();
             // and display the appropriate info
              displayInfo.html("Beverage: " + value.type + "<br>" + "Number of Cups: " + value.cups + "<br>" + "Added Ingredient(s): " + value.ingredient + "<br>" + "Size: " + value.size);
          });
        });
      });
    });
  </script>
  <style>
    body {
      font-family: 'Karla', sans-serif;
      background-image: url("images/coffeeBg.jpg");
      background-size: cover;
      background-repeat: no-repeat;
      color: #FFF;
      overflow-x: hidden;
    }

    .container {
      width: 100%;
      text-align: center;
      padding: 30px;
    }

    #receiptInfo {
      margin: 40px auto;
      border: 2px solid #000;
      width: 40%;
      padding: 5px 20px;
      line-height: 30px;
      display: none;
      background-color: #FFF;
      color: #000;
    }

    img {
      padding: 0;
      margin: 0;
    }

    #instructions {
      color: #CCC;
      font-size: 13px;
    }
    label {
      display:inline-block;
      width:200px;
      margin-right:30px;
      text-align:right;
    }
  fieldset{
    border:none;
    width:500px;
    margin:0px auto;
  }
  </style>
</head>

<body>
  <div class="container">
    <label>Type in order number :</label>
    <input type="text" id="searchText" name="searchText" />
    <p id="instructions"><i>Your order number must be between 0 and 5.</i></p>
    <button id="getData" value="load data">Print Receipt</button><br><br><br><br>

    <h3>Place an order:</h3>
    <form method="get" action="index.php">
      <p><label>Beverage name: </label><input type="text" size="24" maxlength="100" name="beverage_type" required></p>
      <p><label>Number of cups: </label><input type="number" size="24" maxlength="100" name="beverage_cups" required></p>
      <p><label>Added ingredient(s): </label><input type="text" size="24" maxlength="40" name="beverage_ingredient" min="1" max="100" required></p>
      <p><label>Size: </label><input type="text" size="24" maxlength="40" name="beverage_size" min="1" max="100" required></p>
      <p><input type="submit" name="submit" value="Print Receipt" id=buttonSearch /></p>
    </form>

    <div id="receiptInfo">
      <img src="images/coffeeLogo.png" alt="coffee logo" width="100" height="100">
      <h1>Coffee Co.</h1>
      <h2>Customer Receipt</h2>
      <p>Cashier: <i>Amanda</i></p>
      <p><b><i>Here are the details of your order:</i></b></p>
      <div id="showData"></div>
    </div>
  </div>

</body>

</html>
