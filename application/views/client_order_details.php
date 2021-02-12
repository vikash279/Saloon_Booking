<?php  require_once(APPPATH.'views/header_new.php'); ?>
<?php  require_once(APPPATH.'views/sidebar_new.php'); ?>
<div id="main">
        <div class="row">
            <div class="col s12">
                <div class="container">
	<ol class="breadcrumb">
        <li><a href="<?php echo base_url('Welcome/dashboard'); ?>">Dashboard</a></li>
        <li class="active">Client Order Details</li>        
      </ol>
      
      <div class="message">
  <h2 style="color: #32c5d2!important;"><strong><?php echo $this->session->flashdata('success'); ?></strong></h2>
                    <div class="row">
                      <div class="col s12">
                      	<div class="">
						<!--<h4><i class="fa fa-line-chart"></i>-->
						<!--	<span class="bold uppercase">Category</span>-->
							<!--<a align="right" href="#"><button class="btn btn-primary btn-sm">Add Services</button></a></h4>-->
								
                      	

						</div>
                      </div>
                       </div>
              </div> 
                     <br><br>
                     <div class="col s12">
                      <table class="table table-striped" id="services">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Client Name</th>
                            <th>Product Number</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Delivery Status</th>
                            <th>Image</th>
                          </tr>
                        </thead>
                        <tbody>
                              <?php 
                            if($clientorderdetails){
                                $start = 0;
                               foreach($clientorderdetails as $val){ 
                                   $base_64 = base64_encode($val['id']);
                                   $encryptid = rtrim($base_64, '='); 
                                   
                                   
                             ?>
                             
                          <tr>
                            <td><?php echo ++$start ?></td>
                            <td><?php echo $val['full_name'] ?></td>
                            <td><?php echo $val['name'] ?></td>
                            <td><?php echo $val['category'] ?></td>
                            <td><?php echo $val['description'] ?></td>
                            <td><?php echo $val['price'] ?></td>
                            <td><?php echo $val['quantity'] ?></td>
                            <td><?php echo $val['delivery_status'] ?></td>
                            <td><img src="<?php echo base_url('image/products/').$val['image'] ?>" width="50" height="60"></td>
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
        $('#services').DataTable();
    } );

</script>    
      
      
</section>
<?php  require_once(APPPATH.'views/footer_new.php'); ?>   
