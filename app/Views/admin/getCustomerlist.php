<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php include APPPATH.'Views/layouts/sidebar.php';?>
<section class="home">
        <div class="container">

        <!-- Breadcrumbs-->
    <?php include APPPATH.'Views/layouts/breadcrumb.php';?>  
    <!-- Page Content -->
    <h1>Contact List</h1>
    <hr>


    <?php  if(!empty($userslist)) { ?>


    <table class="table table-striped table-bordered">
  <thead >
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Email Id</th>
      <th scope="col">Contact No</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($userslist as $userdata) { ?>
  
    <tr>
      <th scope="row"><?php echo $userdata['id']; ?></th>
      <td><?php echo $userdata['firstname']." ".$userdata['lastname']; ?></td>
      <td><?php echo $userdata['email_id']; ?></td>
      <td><?php echo $userdata['contact_details']; ?></td> 
</tr>
  <?php } ?>
  </tbody>

</table>
<?php  } else { ?>
    <div class="text-center"><p class="fs-3"> <span class="text-danger">Oops!</span>No records found.</p>
    </div>
  <?php } ?>
</div>
</section>

<?= $this->endSection() ?>
