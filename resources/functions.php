<?php

######################HELPER FUNCTIONS ##############################
function redirect($location) {
    header("Location: $location");
}

function query($sql) {
    global $connection;
    return mysqli_query($connection, $sql);
}

function confirm($result){
    global $connection;
    if(!$result) {
        die("QUERY FAILED " . mysqli_error($connection));
    }
}

function escape($str){
    global $connection;
    return mysqli_real_escape_string($connection, $str);
}

function fetch_array($result) {
    return mysqli_fetch_array($result);
}

function set_message($msg) {
    if(!empty($msg)) {
        $_SESSION['message'] = $msg;
    } else {
        $msg = "";
    }
}

function display_message() {
    if(isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

######################FRONTEND FUNCTIONS##############################

function get_products()
{

    $query = query(" SELECT * FROM products");
    confirm($query);

    $rows = mysqli_num_rows($query);
    if(isset($_GET['page'])){

        $page = preg_replace('#[^0-9]#', '', $_GET['page']);//filter everything but numbers



    } else{// If the page url variable is not present force it to be number 1

        $page = 1;

    }


    $perPage = 6; // Items per page here

    $lastPage = ceil($rows / $perPage); // Get the value of the last page


// Be sure URL variable $page(page number) is no lower than page 1 and no higher than $lastpage

    if($page < 1){ // If it is less than 1

        $page = 1; // force if to be 1

    }elseif($page > $lastPage){ // if it is greater than $lastpage

        $page = $lastPage; // force it to be $lastpage's value

    }



    $middleNumbers = ''; // Initialize this variable

// This creates the numbers to click in between the next and back buttons


    $sub1 = $page - 1;
    $sub2 = $page - 2;
    $add1 = $page + 1;
    $add2 = $page + 2;



    if($page == 1){

        $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">' .$add1. '</a></li>';

    } elseif ($page == $lastPage) {

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">' .$sub1. '</a></li>';
        $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';

    }elseif ($page > 2 && $page < ($lastPage -1)) {

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub2.'">' .$sub2. '</a></li>';

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$sub1.'">' .$sub1. '</a></li>';

        $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">' .$add1. '</a></li>';

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add2.'">' .$add2. '</a></li>';




    } elseif($page > 1 && $page < $lastPage){

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page= '.$sub1.'">' .$sub1. '</a></li>';

        $middleNumbers .= '<li class="page-item active"><a>' .$page. '</a></li>';

        $middleNumbers .= '<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$add1.'">' .$add1. '</a></li>';





    }


// This line sets the "LIMIT" range... the 2 values we place to choose a range of rows from database in our query


    $limit = 'LIMIT ' . ($page-1) * $perPage . ',' . $perPage;




// $query2 is what we will use to to display products with out $limit variable

    $query2 = query(" SELECT * FROM products $limit");
    confirm($query2);


    $outputPagination = ""; // Initialize the pagination output variable


// if($lastPage != 1){

//    echo "Page $page of $lastPage";


// }


    // If we are not on page one we place the back link

    if($page != 1){


        $prev  = $page - 1;

        $outputPagination .='<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$prev.'">Back</a></li>';
    }

    // Lets append all our links to this variable that we can use this output pagination

    $outputPagination .= $middleNumbers;


// If we are not on the very last page we the place the next link

    if($page != $lastPage){


        $next = $page + 1;

        $outputPagination .='<li class="page-item"><a class="page-link" href="'.$_SERVER['PHP_SELF'].'?page='.$next.'">Next</a></li>';

    }

    while($row = fetch_array($query2)) {
        $image = display_image($row['product_image']);
        $desc_trim = substr($row['product_description'], 0, 40) . '...';

        $product = <<<DELIMETER

<div class="col-sm-4 col-lg-4 col-md-4">
    <div class="thumbnail">
        <a href="item.php?id={$row['product_id']}"><img style="height: 90px" src="../resources/{$image}" alt=""></a>
        <div class="caption">
            <h4 class="pull-right">&#36;{$row['product_price']}</h4>
            <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
            </h4>
            <p>{$desc_trim}</p>
            <a class="btn btn-primary" target="_blank" href="../resources/cart.php?add={$row['product_id']}">Add to cart</a>

        </div>

    </div>
</div>

DELIMETER;

        echo $product;
    }
    echo "<div class='text-center'><ul class='pagination'>{$outputPagination}</ul></div>";
}

function get_categories(){
    $query = "SELECT * FROM categories";
    $send_query = query($query);
    while($row = fetch_array($send_query)) {
        echo "<a href='category.php?id={$row['cat_id']}' class='list-group-item'>{$row['cat_title']}</a>";
    }
}

function get_products_in_cat_page()
{
    $query = query("SELECT * FROM products WHERE product_category_id = " . escape($_GET['id']) . " AND product_quantity >=1");
    confirm($query);
    while($row = fetch_array($query)) {
        $image = display_image($row['product_image']);

        $product = <<<DELIMETER

<div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="../resources/{$image}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>{$row['product_description']}</p>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>

DELIMETER;

        echo $product;
    }


}

function get_products_in_shop_page()
{
    $query = query("SELECT * FROM products WHERE product_quantity >=1");
    confirm($query);
    while($row = fetch_array($query)) {
        $image = display_image($row['product_image']);
        $desc_trim = substr($row['product_description'], 0, 30) . '...';

        $product = <<<DELIMETER

<div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img style="height: 90px" src="../resources/{$image}" alt="">
                    <div class="caption">
                        <h3>{$row['product_title']}</h3>
                        <p>{$desc_trim}</p>
                        <p>
                            <a href="../resources/cart.php?add={$row['product_id']}" class="btn btn-primary">Buy Now!</a> <a href="item.php?id={$row['product_id']}" class="btn btn-default">More Info</a>
                        </p>
                    </div>
                </div>
            </div>

DELIMETER;

        echo $product;
    }
}

function login(){
    if(isset($_POST['submit'])) {
        $username = escape($_POST['username']);
        $password = escape($_POST['password']);
        $query = query("SELECT * FROM users WHERE username = '{$username}' ");
        confirm($query);

        if(mysqli_num_rows($query) == 0) {
            set_message("Your password or username are wrong");
            redirect("login.php");
        } elseif(mysqli_num_rows($query) == 1) {
            $row = fetch_array($query);
            $db_password = $row['user_password'];
//            var_dump($db_password);
            if(password_verify($password, $db_password)) {
                $_SESSION['username'] = $username;
                set_message("Welcome back {$username}");
                redirect("admin");
            } else {
                set_message("Your password or username are wrong");
                redirect("login.php");
            }



        }
    }
}

function send_message(){
    if(isset($_POST['submit'])) {
        $to = "example@gmail.com";
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        $headers = "From: {$name} {$email}";
        $result = mail($to, $subject, $message, $headers);
        if(!$result) {
            set_message("Sorry we could not send your message");
            redirect("contact.php");
        } else {
            set_message("Your message has been sent");
            redirect("contact.php");

        }
    }
}

function last_id(){
    global $connection;
    return mysqli_insert_id($connection);
}

function display_orders() {
    $query = query("SELECT * FROM orders");
    confirm($query);
    while($row = fetch_array($query)) {

        $orders = <<<DELIMETER
<tr>
    <td>{$row['order_id']}</td>
    <td>{$row['order_amount']}</td>
    <td>{$row['order_transaction']}</td>
    <td>{$row['order_currency']}</td>
    <td>{$row['order_status']}</td>
    <td><a class="btn btn-danger" href="../../resources/templates/back/delete_order.php?id={$row['order_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>

DELIMETER;

echo $orders;


    }
}

function get_products_in_admin() {
    $query = query("SELECT * FROM products");
    confirm($query);
    while($row = fetch_array($query)) {
        $cat_title = show_product_category_title($row['product_category_id']);
        $image = display_image($row['product_image']);
        $product = <<<DELIMETER

<tr>
    <td>{$row['product_id']}</td>
    <td>{$row['product_title']}<br>
      <a href="index.php?edit_product&id={$row['product_id']}">
      <img width="100px" src="../../resources/{$image}" alt="">
    
      </a>
    </td>
    <td>{$cat_title}</td>
    <td>{$row['product_price']}</td>
    <td>{$row['product_quantity']}</td>
    <td><a class="btn btn-danger" href="../../resources/templates/back/delete_product.php?id={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>

DELIMETER;

        echo $product;
    }
}


function add_product() {

    if(isset($_POST['publish'])) {
        $product_title = escape($_POST['product_title']);
        $product_category_id = escape($_POST['product_category_id']);
        $product_price = escape($_POST['product_price']);
        $product_description = escape($_POST['product_description']);
        $short_desc = escape($_POST['short_desc']);
        $product_quantity = escape($_POST['product_quantity']);

        $product_image = escape($_FILES['file']['name']);
        $image_temp_location = $_FILES['file']['tmp_name'];

        move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);
$query = query("INSERT INTO products(product_title, product_category_id, product_price, product_description, short_desc, product_image, product_quantity) VALUES('{$product_title}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$short_desc}', '{$product_image}', '{$product_quantity}')");
confirm($query);

$last_id = last_id();

set_message("New Product number {$last_id} just added");
redirect("index.php?products");

    }


}

function show_categories_add_product_page(){
    $query = "SELECT * FROM categories";
    $send_query = query($query);
    while($row = fetch_array($send_query)) {

       $categories_option = <<<DELIMETER
               <option value="{$row['cat_id']}">{$row['cat_title']}</option>

DELIMETER;
echo $categories_option;

    }
}

function show_product_category_title($product_category_id) {
    $category_query = query("SELECT * FROM categories WHERE cat_id = '{$product_category_id}'");
    confirm($category_query);
    while($category_row = fetch_array($category_query)) {
        return $category_row['cat_title'];
    }
}

function display_image($picture) {
    return "uploads" . DS . $picture;
}

function update_product(){
    if(isset($_POST['update'])) {
        $product_id = escape($_GET['id']);
        $product_title = escape($_POST['product_title']);
        $product_category_id = escape($_POST['product_category_id']);
        $product_price = escape($_POST['product_price']);
        $product_description = escape($_POST['product_description']);
        $short_desc = escape($_POST['short_desc']);
        $product_quantity = escape($_POST['product_quantity']);

        $product_image = escape($_FILES['file']['name']);
        $image_temp_location = escape($_FILES['file']['tmp_name']);


        if(empty($product_image)){
            $get_pic = query("SELECT product_image FROM products WHERE product_id = " . escape($_GET['id']) . " ");
            confirm($get_pic);
            $row = fetch_array($get_pic);
            $product_image = $row['product_image'];
        }


        move_uploaded_file($image_temp_location, UPLOAD_DIRECTORY . DS . $product_image);

$update_query = query("UPDATE products SET 
                            product_title = '{$product_title}', 
                            product_category_id = '{$product_category_id}', 
                            product_price = '{$product_price}', 
                            product_description = '{$product_description}', 
                            short_desc = '{$short_desc}', 
                            product_quantity = '{$product_quantity}', 
                            product_image = '{$product_image}' 
                            WHERE product_id = '{$product_id}'
                            ");
confirm($update_query);
set_message("Product has been updated");
redirect("index.php?products");


    }
}

function show_categories_in_admin() {
    $query = query("SELECT * FROM categories");
    confirm($query);

    while($row = fetch_array($query)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        $category = <<<DELIMETER

<tr>
    <td>{$cat_id}</td>
    <td>{$cat_title}</td>
    <td><a class="btn btn-danger" href="../../resources/templates/back/delete_category.php?id={$cat_id}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>

DELIMETER;
        echo $category;

    }
}

function add_category() {
    if(isset($_POST['add_category'])) {
        $cat_title = escape($_POST['cat_title']);
        if (!empty($cat_title)) {
            $query = query("INSERT INTO categories(cat_title) VALUES('{$cat_title}')");
            confirm($query);
            set_message("Category added");
        }
    }
}

function display_users(){
    $query = query("SELECT * FROM users");
    confirm($query);

    while($row = fetch_array($query)) {
        $user_id = $row['user_id'];
        $username = $row['username'];
        $user_email = $row['user_email'];
        $user_password = $row['user_password'];

        $users = <<<DELIMETER

<tr>
    <td>{$user_id}</td>
    <td><img class="admin-user-thumbnail user_image" src="https://via.placeholder.com/62x62" alt=""></td>
    <td>{$username}</td>
    <td>{$user_email}</td>
    <td><a class="btn btn-danger" href="../../resources/templates/back/delete_user.php?id={$user_id}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>

DELIMETER;
        echo $users;

    }
}

function add_user() {
    if(isset($_POST['add_user'])) {
        $username = escape($_POST['username']);
        $email = escape($_POST['user_email']);
        $password = escape($_POST['user_password']);
        $user_photo = escape($_FILES['file']['name']);
        $photo_temp = escape($_FILES['file']['tmp_name']);

        move_uploaded_file($photo_temp, UPLOAD_DIRECTORY . DS . $user_photo);
        $password = password_hash($password, PASSWORD_BCRYPT, ["cost" => 14]);

        $query = query("INSERT INTO users(username, user_email, user_password, user_image) VALUES('{$username}', '{$email}', '{$password}', '{$user_photo}')");
        confirm($query);
        set_message("User created");
        redirect("index.php?users");
    }
}

function get_reports(){
    $query = query("SELECT * FROM reports");
    confirm($query);
    while($row = fetch_array($query)) {

        $reports = <<<DELIMETER

<tr>
    <td>{$row['report_id']}</td>
    <td>{$row['product_id']}</td>
    <td>{$row['order_id']}</td>
    <td>{$row['product_price']}</td>
    <td>{$row['product_title']}</td>
    <td>{$row['product_quantity']}</td>
    <td><a class="btn btn-danger" href="../../resources/templates/back/delete_report.php?id={$row['report_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
</tr>

DELIMETER;

        echo $reports;
    }
}

function add_slides() {
if(isset($_POST['add_banner'])) {
    $slide_title = escape($_POST['banner_title']);
    $slide_image = escape($_FILES['file']['name']);
    $slide_image_loc = escape($_FILES['file']['tmp_name']);

    if(empty($slide_title) || empty($slide_image)) {
        echo "<p class = 'bg-danger'>This field cannot be empty</p>";
    } else {
        move_uploaded_file($slide_image_loc, UPLOAD_DIRECTORY . DS . $slide_image);
        $query = query("INSERT INTO slides(slide_title, slide_image) VALUES('{$slide_title}', '{$slide_image}')");
        confirm($query);
        set_message("Slide Added");
        redirect("index.php?slides");
    }
}
}

function get_active() {
    $query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
    confirm($query);
    while($row = fetch_array($query)) {
        $image = display_image($row['slide_image']);

        $slide_active = <<<DELIMETER

        <div class="item active">
            <img width="800px" height="300px" class="slide-image" src="../resources/{$image}" alt="">
        </div>
DELIMETER;
        echo $slide_active;

    }
}

function get_current_slide_in_admin() {
    $query = query("SELECT * FROM slides ORDER BY slide_id DESC LIMIT 1");
    confirm($query);
    while($row = fetch_array($query)) {
        $image = display_image($row['slide_image']);

        $slide_active_admin = <<<DELIMETER

            <img class="img-responsive" src="../../resources/{$image}" alt="">
DELIMETER;
        echo $slide_active_admin;

    }
}

function get_slides() {

    $query = query("SELECT * FROM slides");
    confirm($query);
    while($row = fetch_array($query)) {
        $image = display_image($row['slide_image']);

        $slides = <<<DELIMETER

        <div class="item">
            <img width="800px" height="300px" class="slide-image" src="../resources/{$image}" alt="">
        </div>
DELIMETER;
echo $slides;

    }
}

function get_slide_thumbnails() {
    $query = query("SELECT * FROM slides ORDER BY slide_id ASC");
    confirm($query);
    while($row = fetch_array($query)) {
        $image = display_image($row['slide_image']);

        $slide_thumb_admin = <<<DELIMETER
<div class="col-xs-6 col-md-3 image_container">
    <a href="index.php?delete_slide={$row['slide_id']}">
     <img  class="img-thumbnail" src="../../resources/{$image}" alt="">
    </a>
    <div class="caption">
        <p>{$row['slide_title']}</p>
    </div>
</div>
DELIMETER;
        echo $slide_thumb_admin;

    }
}

function register() {
    if(isset($_POST['register'])) {
        $username = escape($_POST['username']);
        $password = escape($_POST['password']);
        $firstName = escape($_POST['first_name']);
        $lastName = escape($_POST['last_name']);
        $email = escape($_POST['email']);


        $password = password_hash($password, PASSWORD_BCRYPT, ["cost" => 14]);

        $query = query("INSERT INTO users(username, user_email, user_password, user_image) VALUES('{$username}', '{$email}', '{$password}', 'NULL')");
        confirm($query);

    }

}
