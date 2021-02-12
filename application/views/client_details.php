<?php  require_once(APPPATH.'views/header_new.php'); ?>
<?php  require_once(APPPATH.'views/sidebar_new.php'); ?>
<div id="main">
        <div class="row">
            <div class="col s12">
                <div class="container">
	<ol class="breadcrumb">
        <li><a href="<?php echo base_url('Welcome/dashboard'); ?>">Dashboard</a></li>
        <li class="active">Client Details</li>        
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
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Image</th>
                            <th>Appointments/Bookings</th>
                          </tr>
                        </thead>
                        <tbody>
                              <?php 
                            if($clientdetails){
                                $start = 0;
                               foreach($clientdetails as $val){ 
                                   $base_64 = base64_encode($val['id']);
                                   $encryptid = rtrim($base_64, '='); 
                                   
                                   
                             ?>
                             
                          <tr>
                            <td><?php echo ++$start ?></td>
                            <td><?php echo $val['full_name'] ?></td>
                            <td>+<?php echo $val['country_code'].$val['mobile_number'] ?></td>
                            <td><?php echo $val['email'] ?></td>
                            <td><?php echo $val['gender'] ?></td>
                            <td><img src="<?php echo base_url('image/profile/').$val['image'] ?>"></td>
                            <td><a href="<?php base_url(); ?>clientBookingDetails/<?php echo $encryptid; ?>"  class='EditBtn btn green btn-sm'>
                            <span class='glyphicon glyphicon-eye-open'></span>
                            </a></td>
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
