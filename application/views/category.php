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
                      	<div class="">
						<!--<h4><i class="fa fa-line-chart"></i>-->
						<!--	<span class="bold uppercase">Category</span>-->
							<a align="right" href="<?php echo base_url('Welcome/addShopCategory'); ?>"><button class="btn btn-primary btn-sm">Add Category</button></a></h4>
								
                      	

						</div>
                      </div>
                       </div>
              </div> 
                     <br><br>
                     <div class="col s12">
                      <table class="table table-striped" id="category">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Shop Name</th>
                            <th>Shop Type</th>
                            <th>Phone</th>
                            <th>Category</th>
                            <!--<th>Action</th>-->
                          </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($category){
                                $start = 0;
                               foreach($category as $val){ 
                                   $base_64 = base64_encode($val['id']);
                                   $encryptid = rtrim($base_64, '=');  
                             ?>
                             
                          <tr>
                             <td><?php echo ++$start ?></td> 
                            <td><?php echo $val['shop_name'] ?></td>
                            <td><?php echo $val['user_type'] ?></td>
                            <td><?php echo $val['shop_phone_number'] ?></td>
                            <td><a href="<?php base_url(); ?>shopCategories/<?php echo $encryptid; ?>"  class='EditBtn btn green btn-sm'>
                          <span class='glyphicon glyphicon-eye-open'></span>
                        </a></td>
                      <!--      <td><a href="#" data-toggle='confirmation' id='deleteadd' data-placement='left' class='btn red btn-sm deleteconform' onclick="return confirm('Are you sure you want to Delete?');">-->
                      <!--  <span class='glyphicon glyphicon-trash'></span>-->
                      <!--</a></td>-->
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
        $('#category').DataTable();
    } );

</script>    
      
      

<?php  require_once(APPPATH.'views/footer_new.php'); ?>   
