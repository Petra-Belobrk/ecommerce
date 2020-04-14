
  <div class="row">

    <h3 class="bg-success"><?php display_message(); ?></h3>

 <div class="col-xs-4">

 <form action="" method="post" enctype="multipart/form-data">
     <?php add_slides();?>


     <div class="form-group">

<input type="file" name="file">

</div>

<div class="form-group">
<label for="title">Slide Title</label>
<input type="text" name="banner_title" class="form-control">

</div>

<div class="form-group">

<input class="btn btn-primary" value="Upload" type="submit" name="add_banner">

</div>

 </form>

 </div>


 <div class="col-xs-8">

     <?php
     get_current_slide_in_admin();
     ?>


 </div>

</div><!-- ROW-->

<hr>

<h1>Slides Available</h1>

<div class="row">

<?php
get_slide_thumbnails();
?>

</div>


