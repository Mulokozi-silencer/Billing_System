<?php
session_start();
include ("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user data from form
    $firstname = $_POST['firstname'];
    $surname = $_POST['surname'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['number'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $borough = $_POST['borough'];
    $postcode = $_POST['postcode'];

    // Insert user data into users table
    $sql = "INSERT INTO users (firstname, surname, dob, gender, email, phone, address, city, borough, postcode) VALUES ('$firstname', '$surname', '$dob', '$gender', '$email', '$phone', '$address', '$city', '$borough', '$postcode')";

    if ($conn->query($sql) === TRUE) {
        $user_id = $conn->insert_id; // Get the last inserted user_id

        // Get order data from form
        $bmeal = $_POST['bmeal'];
        $lfries = $_POST['lfries'];
        $fmeal = $_POST['fmeal'];
        $cmeal = $_POST['cmeal'];
        $mcheese = $_POST['mcheese'];
        $drinks = $_POST['drinks'];
        
        $subtotal = ($bmeal * 1500) + ($lfries * 1000) + ($fmeal * 1400) + ($cmeal * 10000) + ($mcheese * 5400) + ($drinks * 600);
        $tax = $subtotal * 0.15;
        $total = $subtotal + $tax;

        // Insert order data into orders table
        $sql = "INSERT INTO orders (user_id, bmeal, lfries, fmeal, cmeal, mcheese, drinks, tax, subtotal, total) VALUES ('$user_id', '$bmeal', '$lfries', '$fmeal', '$cmeal', '$mcheese', '$drinks', '$tax', '$subtotal', '$total')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Order placed successfully! Total cost: Tsh $total');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
        }

    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Billing System</title>
    <style type="text/css">
       .container {
           display: flex;
           justify-content: space-between;
        }
        .billing-form, .calculator {
            flex: 0 0 48%; /* Adjust the width as needed */
        }
          
        .container {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Adjust column width as needed */
            grid-column-gap: 20px; /* Adjust gap between columns as needed */
        }

        .style1 {
            border: solid gray 5px;
            width: 90%;
            border-radius: 5px;
            margin: 5px auto;
            background: transparent;
            display: flex;
        }

        .style2 {
            border: solid gray 10px;
            width: 90%;
            border-radius: 5px;
            margin: 5px auto;
            background: white;
            color: #ddd333;
        }

        .btn {
           width: 202px;
           height: 30px;
           padding: 5px;
           background: gray;
           font-size: 20px;
           color: white;
           border: none;
           border-radius: 5px;
        }

        .cbtn {
           width: 50px;
           height: 40px;
           font-size: 25px;
           border-radius: 8px;
           margin: 3px;
        }

        body {
            background-color: #000;
            color: white;
            text-align: center;
        }

        hr {
            margin: 20px auto;
            width: 90%;
        }

        input[type="text"], input[type="email"], input[type="date"], textarea {
            width: 200px;
        }
        
    </style>
</head>
<body>
    <center>
        <img src="Images/" alt="">
        <b><font style="font-size: 80px;">Billing System</font></b>
    </center>
    <hr size="5" color="gray">

    <div class="container">
        <form id="billingForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" onsubmit="return calculateTotal()">
            <table class="style1">
                <tr>
                    <td>Firstname:</td>
                    <td><input type="text" name="firstname" placeholder="Firstname" required></td>
                </tr>
                <tr>
                    <td>Surname:</td>
                    <td><input type="text" name="surname" placeholder="Surname" required></td>
                </tr>
                <tr>
                    <td>Date of Birth:</td>
                    <td><input type="date" name="dob" placeholder="optional"></td>
                </tr>
                <tr>
                    <td>Gender:</td>
                    <td><input type="text" name="gender" placeholder="optional"></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email" required></td>
                </tr>
                <tr>
                    <td>Telephone:</td>
                    <td><input type="text" name="number" required></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><textarea name="address" cols="22" required></textarea></td>
                </tr>
                <tr>
                    <td>City:</td>
                    <td><input type="text" name="city" required></td>
                </tr>
                <tr>
                    <td>Borough:</td>
                    <td><input type="text" name="borough" required></td>
                </tr>
                <tr>
                    <td>Postcode:</td>
                    <td><input type="text" name="postcode" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left"><input type="reset" name="reset" value="Reset" class="btn"></td>
                </tr>
            </table>

            <div class="Order">
            <table class="style1">
                <tr>
                    <td colspan="2"><b><u>Make an Order</u></b></td>
                </tr>
                <tr>
                    <td>Burger Meal (Tsh 1500):</td>
                    <td><input type="number" name="bmeal" value="0" required></td>
                </tr>
                <tr>
                    <td>Large Fries (Tsh 1000):</td>
                    <td><input type="number" name="lfries" value="0" required></td>
                </tr>
                <tr>
                    <td>Filet-o-Meal (Tsh 1400):</td>
                    <td><input type="number" name="fmeal" value="0" required></td>
                </tr>
                <tr>
                    <td>Chicken Meal (Tsh 10000):</td>
                    <td><input type="number" name="cmeal" value="0" required></td>
                </tr>
                <tr>
                    <td>Cheese Meal (Tsh 5400):</td>
                    <td><input type="number" name="mcheese" value="0" required></td>
                </tr>
                <tr>
                    <td>Drinks (Tsh 600):</td>
                    <td><input type="number" name="drinks" value="0" required></td>
                </tr>
                <tr>
                    <td colspan="2"><b><u>Total Cost</u></b></td>
                </tr>
                <tr>
                    <td>Tax:</td>
                    <td><input type="text" id="tax" readonly></td>
                </tr>
                <tr>
                    <td>Subtotal:</td>
                    <td><input type="text" id="subtotal" readonly></td>
                </tr>
                <tr>
                    <td>Total:</td>
                    <td><input type="text" id="total" readonly></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left"><input type="submit" value="Submit" name="add" class="btn"></td>
                </tr>
            </table>
            </div>
        </form>

        <form name="Calculator" class="style2">
            <input name="display" placeholder="0" style="width: 212px; height: 40px; text-align: right; font-size: 30px; border-radius: 8px; margin: 3px;" />
            <br>
            <input type="button" value="7" onclick="document.Calculator.display.value +='7'" class="cbtn" />
            <input type="button" value="8" onclick="document.Calculator.display.value +='8'" class="cbtn" />
            <input type="button" value="9" onclick="document.Calculator.display.value +='9'" class="cbtn" />
            <input type="button" value="+" onclick="btnplus()" class='cbtn' />
            <br>
            <input type="button" value="4" onclick="document.Calculator.display.value +='4'" class="cbtn" />
            <input type="button" value="5" onclick="document.Calculator.display.value +='5'" class="cbtn" />
            <input type="button" value="6" onclick="document.Calculator.display.value +='6'" class="cbtn" />
            <input type="button" value="-" onclick="btnsub()" class='cbtn' />
            <br>
            <input type="button" value="1" onclick="document.Calculator.display.value +='1'" class="cbtn" />
            <input type="button" value="2" onclick="document.Calculator.display.value +='2'" class="cbtn" />
            <input type="button" value="3" onclick="document.Calculator.display.value +='3'" class="cbtn" />
            <input type="button" value="*" onclick="btnmult()" class='cbtn' />
            <br>
            <input type="button" value="0" onclick="document.Calculator.display.value +='0'" class="cbtn" />
            <input type="button" value="%" onclick="btnMod()" class='cbtn' />
            <input type="button" value="." onclick="btndot()" class="cbtn" />
            <input type="button" value="/" onclick="btndiv()" class="cbtn" />
            <br>
            <input type="button" value="=" onclick="document.Calculator.display.value = eval(document.Calculator.display.value)" style="width: 101px; height: 40px; font-size: 30px; border-radius: 8px; margin: 3px;"/>
            <input type="button" value="C" onclick="btnclear()" style="width: 101px; height: 40px; font-size: 30px; border-radius: 8px;" />
        </form>
    </div>

    <hr size="5" color="gray">
    
    <div id="scriptContainer">
    <script>
        function calculateTotal() {
            var bmeal = parseInt(document.querySelector('[name="bmeal"]').value) || 0;
            var lfries = parseInt(document.querySelector('[name="lfries"]').value) || 0;
            var fmeal = parseInt(document.querySelector('[name="fmeal"]').value) || 0;
            var cmeal = parseInt(document.querySelector('[name="cmeal"]').value) || 0;
            var mcheese = parseInt(document.querySelector('[name="mcheese"]').value) || 0;
            var drinks = parseInt(document.querySelector('[name="drinks"]').value) || 0;

            var subtotal = (bmeal * 1500) + (lfries * 1000) + (fmeal * 1400) + (cmeal * 10000) + (mcheese * 5400) + (drinks * 600);
            var tax = subtotal * 0.15;
            var total = subtotal + tax;

            document.getElementById('tax').value = tax;
            document.getElementById('subtotal').value = subtotal;
            document.getElementById('total').value = total;

            return true; // Allow form to submit
        }

        function btnplus() {
            document.Calculator.display.value += "+";
            document.Calculator.display.style.textAlign = "right";
        }

        function btnsub() {
            document.Calculator.display.value += "-";
            document.Calculator.display.style.textAlign = "right";
        }

        function btnmult() {
            document.Calculator.display.value += "*";
            document.Calculator.display.style.textAlign = "right";
        }

        function btnMod() {
            document.Calculator.display.value += "%";
            document.Calculator.display.style.textAlign = "right";
        }

        function btndot() {
            document.Calculator.display.value += ".";
            document.Calculator.display.style.textAlign = "right";
        }

        function btndiv() {
            document.Calculator.display.value += "/";
            document.Calculator.display.style.textAlign = "right";
        }

        function btnclear() {
            document.Calculator.display.value = "";
            document.Calculator.display.style.textAlign = "right";
        }
    </script>
</body>
</html>