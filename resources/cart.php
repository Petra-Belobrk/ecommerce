<?php require_once("config.php"); ?>

<?php

if(isset($_GET['add'])) {
    $query = query("SELECT * FROM products WHERE product_id = " . escape($_GET['add']));
    confirm($query);

    while ($row = fetch_array($query)) {

        if($row['product_quantity'] != $_SESSION['product_' . $_GET['add']]) {
            $_SESSION['product_' . $_GET['add']]+=1;
            redirect("../public/checkout.php");
        } else {
            set_message("We only have " . $row['product_quantity'] . " " . $row['product_title'] . "'s available.");
            redirect("../public/checkout.php");
        }

    }
//    $_SESSION['product_' . $_GET['add']] +=1;
//    redirect("index.php");
}

if(isset($_GET['remove'])) {
    if($_SESSION['product_' . $_GET['remove']] > 0) {
        $_SESSION['product_' . $_GET['remove']]--;
        unset($_SESSION['item_total']);
        unset($_SESSION['item_quantity']);
        redirect("../public/checkout.php");

    } elseif ($_SESSION['product_' . $_GET['remove']] <= 0) {
        redirect("../public/checkout.php");

    }
//    if($_SESSION['product_' . $_GET['remove']] < 1) {
//        redirect("checkout.php");
//    } else {
//        redirect("checkout.php");
//
//    }

}


if(isset($_GET['delete'])) {
    $_SESSION['product_' . $_GET['delete']] = 0;
    unset($_SESSION['item_total']);
    unset($_SESSION['item_quantity']);
            redirect("../public/checkout.php");


}

function cart() {
    $total = 0;
    $item_quantity = 0;
    $item_name = 1;
    $item_number = 1;
    $amount = 1;
    $quantity = 1;
    foreach($_SESSION as $name => $value) {
        if(substr($name, 0, 8) == 'product_') {
            $length = strlen($name);
            $id = substr($name, 8, $length);
            if($value > 0) {
                $query = query("SELECT * FROM products WHERE product_id = " . escape($id) . " ");
                confirm($query);
                while($row = fetch_array($query)){
                    $sub = $row['product_price']*$value;
                    $item_quantity += $value;
                    $image = display_image($row['product_image']);
                    $product = <<<DELIMETER
<tr>
    <td>{$row['product_title']}<br>
    <img width="100px" src="../resources/{$image}">
    </td>
    <td>&#36;{$row['product_price']}</td>
    <td>{$value}</td>
    <td>&#36;{$sub}</td>
    <td><a class="btn btn-warning" href="../resources/cart.php?remove={$row['product_id']}"><span class="glyphicon glyphicon-minus"></span></a>       
    <a class="btn btn-success" href="../resources/cart.php?add={$row['product_id']}"><span class="glyphicon glyphicon-plus"></span></a>      
    <a class="btn btn-danger" href="../resources/cart.php?delete={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>

<input type="hidden" name="item_name_{$item_name}" value="{$row['product_title']}">
<input type="hidden" name="item_number_{$item_number}" value="{$row['product_id']}">
<input type="hidden" name="amount_{$amount}" value="{$row['product_price']}">
<input type="hidden" name="quantity_{$quantity}" value="{$value}">

DELIMETER;

                    echo $product;

                    $item_name++;
                    $item_number++;
                    $amount++;
                    $quantity++;

                }
                 $_SESSION['item_total'] = $total += $sub;
                 $_SESSION['item_quantity'] = $item_quantity;

            }

        }


    }


}

function show_paypal()
{
    if (isset($_SESSION['item_quantity']) && $_SESSION['item_quantity'] >= 1) {
        $paypal_button = <<<DELIMETER
<input type="image" name="upload"
           src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
           alt="PayPal - The safer, easier way to pay online">
DELIMETER;
        echo $paypal_button;

    }
}


function process_transaction() {

    if(isset($_GET['tx'])) {

        $amount = $_GET['amt'];
        $currency = $_GET['cc'];
        $transaction = $_GET['tx'];
        $status = $_GET['st'];


        $total = 0;
        $item_quantity = 0;

        foreach ($_SESSION as $name => $value) {
            if (substr($name, 0, 8) == 'product_') {
                $length = strlen($name) - 8;
                $id = substr($name, 8, $length);
                if ($value > 0) {

                    $send_order = query("INSERT INTO orders(order_amount, order_transaction, order_status, order_currency) VALUES ('{$amount}', '{$transaction}', '{$status}', '{$currency}')");
                    $last_id = last_id();
                    confirm($send_order);

                    $query = query("SELECT * FROM products WHERE product_id = " . escape($id) . " ");
                    confirm($query);
                    while ($row = fetch_array($query)) {
                        $sub = $row['product_price'] * $value;
                        $item_quantity += $value;
                        $product_price = $row['product_price'];
                        $product_title = $row['product_title'];
                        $insert_report = query("INSERT INTO reports(product_id, order_id, product_title, product_price, product_quantity) VALUES ('{$id}', '{$last_id}', '{$product_title}', '{$product_price}', '{$value}')");
                        confirm($insert_report);

                    }
                    $total += $sub;

                }

            }


        }    session_destroy();

    } else {
        redirect("index.php");
    }

}
