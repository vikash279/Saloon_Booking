<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">

    <title> Barber App</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo base_url(); ?>tpl/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="<?php echo base_url(); ?>tpl/css/simple-sidebar.css" rel="stylesheet" id="bootstrap-css">
  <!--  <link href="https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
    <link href="<?php echo base_url(); ?>tpl/css/datepicker3.css" rel="stylesheet" id="bootstrap-css">
    <script src="<?php echo base_url(); ?>tpl/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>tpl/js/bootstrap.min.js"></script>
  <!--  <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>-->
    <script src="<?php echo base_url(); ?>tpl/js/bootstrap-datepicker.js"></script>
    <link id="bsdp-css" href="<?php echo base_url(); ?>/tpl/css/bootstrap-datepicker3.css" rel="stylesheet">
    <!--===============================================================================================-->
      <link rel="icon" type="image/png" href="<?php echo base_url(); ?>tpl/login/images/icons/favicon.ico"/>
    <!--===============================================================================================-->

    <!--===============================================================================================-->
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>tpl/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>tpl/login/vendor/animate/animate.css">
    <!--===============================================================================================-->
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>tpl/login/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>tpl/login/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>tpl/login/css/util.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>tpl/login/css/main.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


</head>
<body>
<div class="limiter">
	
		<div class="container-login100">
            <?php if($this->session->flashdata('success')){?>
					<div align="center" class="alert bgcolr">      
						<?php echo $this->session->flashdata('success')?>
					</div>
				<?php } ?> 

				<?php if($this->session->flashdata('message')){?>
					<div align="center" class="alert alert-danger">      
						<?php echo $this->session->flashdata('message')?>
					</div>
				<?php } ?>
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="<?php echo base_url()?>tpl/login/images/img-01.png" alt="IMG">
				</div>
             

				<form class="login100-form validate-form" action="<?php echo base_url('Welcome/login')?>" method="post">
					<span class="login100-form-title">
						Login
					</span>

					<?php if($error =$this->session->flashdata('login_fail')) { ?>
						<div class="row">
							<div class="form-group" style="color: red;">
								<?php echo $error; ?>
							</div>
						</div>
					<?php } ?>			
					<div class="clearfix"></div>
					<br>
						
					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="text" name="username" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>
                    
					<div class="text-center p-t-12">
						<span class="txt1">
							Forgot
						</span>
						<a class="txt2" href="<?php echo base_url();?>Login/resetPassword">
							Password?
						</a>
					</div>

					<div class="text-center p-t-136">
						<a class="txt2" href="#">

							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
<script src="<?php echo base_url(); ?>tpl/login/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<script src="<?php echo base_url(); ?>tpl/login/vendor/bootstrap/js/popper.js"></script>
<script src="<?php echo base_url(); ?>tpl/login/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
<script src="<?php echo base_url(); ?>tpl/login/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
<script src="<?php echo base_url(); ?>tpl/login/vendor/tilt/tilt.jquery.min.js"></script>
