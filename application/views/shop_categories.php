<?php  require_once(APPPATH.'views/header_new.php'); ?>
<?php  require_once(APPPATH.'views/sidebar_new.php'); ?>
<div id="main">
        <div class="row">
            <div class="col s12">
                <div class="container">
	<ol class="breadcrumb">
        <li><a href="<?php echo base_url('Welcome/dashboard'); ?>">Dashboard</a></li>
        <li class="active">Category</li>        
      </ol>
      
      <div class="message">
  <h2 style="color: #32c5d2!important;"><strong><?php echo $this->session->flashdata('success'); ?></strong></h2>
                    <div class="row">
                      <div class="col s12">
                      
                      </div>
                       </div>
              </div> 
                     <br><br>
                     <div class="col s12">
                      <table class="table table-striped" id="shopcategory">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Category Name</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($shopcategory){
                                $start = 0;
                               foreach($shopcategory as $val){ 
                                   $base_64 = base64_encode($val['id']);
                                   $encryptid = rtrim($base_64, '=');  
                             ?>
                             
                          <tr>
                             <td><?php echo ++$start ?></td> 
                            <td><?php echo $val['cat_name'] ?></td>
                            <td><a href="<?php base_url(); ?>deleteShopCategories/<?php echo $encryptid; ?>" data-toggle='confirmation' id='deleteadd' data-placement='left' class='btn red btn-sm deleteconform' onclick="return confirm('Are you sure you want to Delete?');">
                        <span class='glyphicon glyphicon-trash'></span
                      </a></td
                          </tr>
                         <?php } } ?>
                        </tbody>
                      </table>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                 
      
      
   <script>
    $(document).ready(function() {
        $('#shopcategory').DataTable();
    } );

</script>    
      
      
</section>
<?php  require_once(APPPATH.'views/footer_new.php'); ?>   
