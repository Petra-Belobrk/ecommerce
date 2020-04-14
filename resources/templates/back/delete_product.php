<?php
require_once("../../config.php");

if(isset($_GET['id'])) {
    $query = query("DELETE FROM products WHERE product_id = " . escape($_GET['id']) . " ");
    confirm($query);
    set_message("Product deleted");

    redirect("../../../public/admin/index.php?products");
} else {
    redirect("../../../public/admin/index.php?products");

}