<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<?php include APPPATH.'Views/layouts/sidebar.php';?>
<?php echo script_tag('js/jquery.min.js'); ?>
<section class="home">
        <div class="container">
        <!-- Breadcrumbs-->
    <?php include APPPATH.'Views/layouts/breadcrumb.php';?>  
    <!-- Page Content -->
    <h1>Answer List for Survey Question</h1>
    <hr>    
    <?php if (session()->getFlashdata('response') !== NULL) : ?>
            <p style="color:green; font-size:18px;"  align="center"><?php echo session()->getFlashdata('response'); ?></p>
        <?php endif; ?>
<div class="row">
<div class="col-xl-11 col-lg-11 col-md-11">     
    <div class="text-center mb-5"><a class="btn btn-primary float-end" href="<?php echo site_url('createAnswer'); ?>">Create Answer</a>
    </div>
  </div>
</div>
<?php  if(!empty($getAnswerData)) { ?>

    <table class="table mt-6 table-striped table-bordered">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Answer Details</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($getAnswerData as $answerData) { ?>
    <tr>
      <td scope="row"><?php echo $answerData['answer_id']; ?></td>
      <td><?php echo stripslashes($answerData['answer_name']); ?></td>
      <td><a class="btn btn-primary" href="<?php echo site_url('editAnswer/'.$answerData['answer_id']); ?>">edit</a>
      <a class="btn btn-primary" href="<?php echo site_url('deleteAnswer/'.$answerData['answer_id']); ?>">delete</a></td>
    </tr>
  <?php  } ?>
  </tbody>

  </table>
  <?php  } else { ?>
    <div class="text-center"><p class="fs-3"> <span class="text-danger">Oops!</span>No records found.</p>
    </div>
  <?php } ?>
  </div>
  </section>
    <?= $this->endSection() ?>