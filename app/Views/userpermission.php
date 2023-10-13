<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php include APPPATH.'Views/layouts/sidebar.php';?>
<section class="home">
        <div class="container">

        <!-- Breadcrumbs-->
    <?php include APPPATH.'Views/layouts/breadcrumb.php';?>  
    <!-- Page Content -->
    <h1>User permission List</h1>
    <hr>
    <?php echo script_tag('js/jquery.min.js'); ?>


<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

    <table class="table table-striped table-bordered">
  <thead >
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Email Id</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($users as $userdata) { ?>
  
    <tr>
      <th scope="row"><?php echo $userdata['id']; ?></th>
      <td><?php echo $userdata['firstname']." ".$userdata['lastname']; ?></td>
      <td><?php echo $userdata['email']; ?></td>
      <td>  
    <label class="checkbox-inline bootstrap-switch-noPad" >
    <input type="checkbox" data-toggle="toggle" value="<?php echo $userdata['role']; ?>" class="role_<?php echo $userdata['id']; ?>" data-size="xs" data-on="admin" data-off="user" data-onstyle="success" data-offstyle="danger" id="toggle_show_cancelled<?php echo $userdata['id']; ?>" name="recipients" <?php if($userdata['role'] == 'admin') { ?> checked = "checked" <?php } ?>>
    </label>

</td>
    
  <script type="text/javascript">
    $("#toggle_show_cancelled<?php echo $userdata['id']; ?>").change(function(){
    if($(this).prop("checked") == true){
      var dataswitch = $(this).attr("data-on");
    }else{
      var dataswitch = $(this).attr("data-off");
    }
    console.log(dataswitch);
    var query = dataswitch;
    $.ajax({  
      url:'<?php echo base_url('changerole'); ?>',
      type: 'post',
      dataType:'json',
      data: {query: query, id: '<?php echo $userdata['id']; ?>'},
      success: function(response){
        var result = JSON.parse(response);
        console.log(response);
        console.log(result);
      },
      error: function(response){
        console.log(response);
      } 
    });
});

</script>
</tr>
  <?php } ?>
  </tbody>

</table>
</div>
</section>

<?= $this->endSection() ?>
