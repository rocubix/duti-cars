<?php
/**
 * Created by PhpStorm.
 * User: Andrei
 * Date: 11/23/2018
 * Time: 7:57 PM
 */
include "../../src/database.php";
const AVAILABILITY_UNAVAILABLE = 0;
const AVAILABILITY_AVAILABLE = 1;
const AVAILABILITY_RESERVED = 2;
const AVAILABILITY_SOLD = 3;


//echo '<pre>';
//var_dump($_GET);
//echo '</pre>';

$form['data']['productCode'] = isset($_GET['productCode']) ? $_GET['productCode'] : '';
$form['data']['brand'] = isset($_GET['brand']) ? $_GET['brand'] : '';
$form['data']['model'] = isset($_GET['model']) ? $_GET['model'] : '';
$form['data']['price'] = isset($_GET['price']) ? $_GET['price'] : '';
$form['data']['availability'] = isset($_GET['availability']) ? $_GET['availability'] : '';
$form['data']['description'] = isset($_GET['description']) ? $_GET['description'] : '';


if (isset($_GET['id']) && $_GET['id'] != '') {

    $sql = "SELECT * FROM products WHERE id = " . $_GET['id'];
    $result = $DB->query($sql);

    $form['data'] = $result->fetch_assoc();
    $form['data']['productCode'] = $form['data']['product_code'];
    unset($form['data']['product_code']);

}


if (isset($_GET['save']) && $_GET['save'] == true) {

    $form['data']['productCode'] = isset($_GET['productCode']) ? $_GET['productCode'] : '';
    $form['data']['brand'] = isset($_GET['brand']) ? $_GET['brand'] : '';
    $form['data']['model'] = isset($_GET['model']) ? $_GET['model'] : '';
    $form['data']['price'] = isset($_GET['price']) ? $_GET['price'] : '';
    $form['data']['availability'] = isset($_GET['availability']) ? $_GET['availability'] : '';
    $form['data']['description'] = isset($_GET['description']) ? $_GET['description'] : '';

    $form['success'] = false;
    $form['error'] = null;

    if (
        (isset($_GET['productCode']) && $_GET['productCode'] != '') &&
        (isset($_GET['brand']) && $_GET['brand'] != '') &&
        (isset($_GET['model']) && $_GET['model'] != '') &&
        (isset($_GET['price']) && $_GET['price'] != '') &&
        (isset($_GET['availability']) && $_GET['availability'] != '')
    ) {
        if (strlen($_GET['productCode']) == 8) {
            if (is_float((float)$_GET['price']) && $_GET['price'] > 0) {
                if (is_numeric($_GET['availability'])) {
//                    stripping special characters
                    $form['data']['productCode'] = htmlspecialchars($form['data']['productCode'], ENT_QUOTES);
                    $form['data']['brand'] = htmlspecialchars($form['data']['brand'], ENT_QUOTES);
                    $form['data']['model'] = htmlspecialchars($form['data']['model'], ENT_QUOTES);
                    $form['data']['description'] = htmlspecialchars($form['data']['description'], ENT_QUOTES);
                    if (isset($form['data']['id']) && $form['data']['id'] != '') {
//                    updating database
                        $sql = "
                            UPDATE products
                            SET product_code = '" . $form['data']['productCode'] . "',
                            brand = '" . $form['data']['brand'] . "',
                            model = '" . $form['data']['model'] . "',
                            price = '" . $form['data']['price'] . "',
                            availability = '" . $form['data']['availability'] . "',
                            description = '" . $form['data']['description'] . "'
                            WHERE id = " . $form['data']['id'] . ";
                            
                            ";
                    } else {
//                    inserting into database
                        $sql = "INSERT INTO products VALUES (
                        null ,
                        '" . $form['data']['productCode'] . "',
                        '" . $form['data']['brand'] . "',
                        '" . $form['data']['model'] . "',
                        '" . str_replace(',', '.', $form['data']['price']) . "',
                        '" . $form['data']['availability'] . "',
                        '" . $form['data']['description'] . "',
                        null,
                        NOW()
                        );";
                    }
                    /** @var $DB mysqli */
                    if ($DB->query($sql)) {
                        if (!isset($form['data']['id'])) {
                            $form['data']['id'] = $DB->insert_id;
                            $form['message'] = "Succesfuly insterted into db";
                        } else {
                            $form['message'] = "Succesfuly updated into db";
                        }
                        header('Location: /admin/products/products.php?id=' . $form['data']['id'] . "&message=" . $form['message']);
                        $form['success'] = true;
                    } else {
                        $form['error'][] = mysqli_error($DB);
                    }
                } else {
                    $form['error'][] = "the availability value is incorrect";
                }
            } else {
                $form['error'][] = "the price in incorrect";
            }
        } else {
            $form['error'][] = "the product code in incorrect";
        }
    } else {
        $form['error'][] = "all required values must be filled";
    }
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<style>
    * {
        box-sizing: border-box;
    }

    body {
        background-color: #f1f1f1;
    }

    #regForm {
        background-color: #ffffff;
        margin: 100px auto;
        font-family: Raleway;
        padding: 40px;
        width: 70%;
        min-width: 300px;
    }

    h1 {
        text-align: center;
    }

    input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    select {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
        background-color: #ffdddd;
    }

    select {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    select.invalid {
        background-color: #ffdddd;
    }

    form-control {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    /* Hide all steps by default: */
    .tab {
        display: none;
    }

    button {
        background-color: #4CAF50;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        font-size: 17px;
        font-family: Raleway;
        cursor: pointer;
    }

    button:hover {
        opacity: 0.8;
    }

    #prevBtn {
        background-color: #bbbbbb;
    }

    /* Make circles that indicate the steps of the form: */
    .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    .step.active {
        opacity: 1;
    }

    /* Mark the steps that are finished and valid: */
    .step.finish {
        background-color: #4CAF50;
    }
</style>
<form id="regForm" action="/admin/images/index.php">
    <h1>Add/Edit Products:</h1>
    <!-- One "tab" for each step in the form: -->
    <div class="panel-body">
        <div class="tab">
            <div class="col-sm-10">
                <p><input placeholder="Product Code..." type="text" id="productCode" oninput="this.className = ''"
                          name="productCode" value="<?= $form['data']['productCode'] ?>"></p>
            </div>
            <div class="col-sm-10">
                <p><input placeholder="Brand..." type="text" id="brand" oninput="this.className = ''" name="brand"
                          value="<?= $form['data']['brand'] ?>"></p>
            </div>
            <div class="col-sm-10">
                <p><input placeholder="Model..." type="text" id="model" oninput="this.className = ''" name="model"
                          value="<?= $form['data']['model'] ?>"></p>
            </div>
            <div class="col-sm-10">
                <p><input placeholder="Price..." type="text" id="price" oninput="this.className = ''" name="price"
                          value="<?= $form['data']['price'] ?>"></p>
            </div>
            <div class="col-sm-10" aria-placeholder=" select...">

                <select class="form-control" id="list" oninput="this.className = ''" name="availability"> Please select something

                    <option id="availability"
                            value="<?= AVAILABILITY_UNAVAILABLE ?>"<?= ($form['data']['availability'] == AVAILABILITY_UNAVAILABLE) ? ' selected' : '' ?>>
                        Unavailable
                    </option>
                    <option id="availability"
                            value="<?= AVAILABILITY_AVAILABLE ?>"<?= ($form['data']['availability'] == AVAILABILITY_AVAILABLE) ? ' selected' : '' ?>>
                        Available
                    </option>
                    <option id="availability"
                            value="<?= AVAILABILITY_RESERVED ?>"<?= ($form['data']['availability'] == AVAILABILITY_RESERVED) ? ' selected' : '' ?>>
                        Reserved
                    </option>
                    <option id="availability"
                            value="<?= AVAILABILITY_SOLD ?>"<?= ($form['data']['availability'] == AVAILABILITY_SOLD) ? ' selected' : '' ?>>
                        Sold
                    </option>
                    <option id="availability" value="4" selected=selected > Availability... </option>
                </select>
            </div>
            <div class="col-sm-10">
                <p><input placeholder="Description..." type="text" id="description" oninput="this.className = ''"
                          name="description" value="<?= $form['data']['description'] ?>"></p>
            </div>
        </div>
    </div>
    <div class="tab">Characteristics:
        <p><input placeholder="Name of characteristics..." type="text" id="characteristic" oninput="this.className = ''"
                  name="name"></p>
        <p><input placeholder="Tipe of characteristics..." type="text" id="title" oninput="this.className = ''"
                  name="title"></p>

    </div>
    <div class="tab">Birthday:
        <p><input placeholder="dd" oninput="this.className = ''" name="dd"></p>
        <p><input placeholder="mm" oninput="this.className = ''" name="nn"></p>
        <p><input placeholder="yyyy" oninput="this.className = ''" name="yyyy"></p>
    </div>
    <div class="tab">Login Info:
        <p><input placeholder="Username..." oninput="this.className = ''" name="uname"></p>
        <p><input placeholder="Password..." oninput="this.className = ''" name="pword" type="password"></p>
    </div>
    <div style="overflow:auto;">
        <div style="float:right;">
            <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
            <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
        </div>
    </div>
    <!-- Circles which indicates the steps of the form: -->
    <div style="text-align:center;margin-top:40px;">
        <span class="step"></span>
        <span class="step"></span>
        <span class="step"></span>
        <span class="step"></span>
    </div>
</form>

<script>
    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the crurrent tab

    function getSelectValue() {
        var selectedValue = document.getElementById("list").value;
    }

    function showTab(n) {
        // This function will display the specified tab of the form...
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        //... and fix the Previous/Next buttons:
        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }
        if (n == (x.length - 1)) {
            document.getElementById("nextBtn").innerHTML = "Submit";
        } else {
            document.getElementById("nextBtn").innerHTML = "Next";
        }
        //... and run a function that will display the correct step indicator:
        fixStepIndicator(n)
    }

    function nextPrev(n) {
        // This function will figure out which tab to display
        var x = document.getElementsByClassName("tab");
        // Exit the function if any field in the current tab is invalid:
        if (n == 1 && !validateForm()) return false;
        // Hide the current tab:
        x[currentTab].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentTab = currentTab + n;
        // if you have reached the end of the form...
        if (currentTab >= x.length) {
            // ... the form gets submitted:
            document.getElementById("regForm").submit();
            return false;
        }
        // Otherwise, display the correct tab:
        showTab(currentTab);
    }

    function validateForm() {
        // This function deals with validation of the form fields
        var x, y, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:
        for (i = 0; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
                // add an "invalid" class to the field:
                y[i].className += " invalid";
                // and set the current valid status to false
                valid = false;
            }
        }
        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }
        return valid; // return the valid status
    }

    function fixStepIndicator(n) {
        // This function removes the "active" class of all steps...
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class on the current step:
        x[n].className += " active";
    }
</script>
