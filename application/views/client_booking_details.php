<?php  require_once(APPPATH.'views/header_new.php'); ?>
<?php  require_once(APPPATH.'views/sidebar_new.php'); ?>
<div id="main">
        <div class="row">
            <div class="col s12">
                <div class="container">
	<ol class="breadcrumb">
        <li><a href="<?php echo base_url('Welcome/dashboard'); ?>">Dashboard</a></li>
        <li class="active">Client Booking Details</li>        
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
                            <th>Shop Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Comment</th>
                            <th>Service Booked</th>
                            
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
                            <td>+<?php echo $val['shop_name'] ?></td>
                            <td><?php echo $val['date'] ?></td>
                            <td><?php echo $val['time'] ?></td>
                            <td><?php echo $val['comment'] ?></td>
                            <td><?php echo $val['service'] ?></td>
                            
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
