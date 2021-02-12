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
							<!--<a align="right" href="#"><button class="btn btn-primary btn-sm">Add Category</button></a></h4>-->
								
                      	

						</div>
                      </div>
                       </div>
              </div> 
                     <br><br>
                     
                       <div class="col s12">
                                <div id="placeholder" class="card card card-default scrollspy">
                                    <div class="card-content">
                                        <h4 class="card-title">Add Category To Shop</h4>
                                        <form method="POST" action="<?php echo base_url('Welcome/submitShopCategory'); ?>">
                                             
                                            <div class="row">
                                            <div class="input-field col s12">
                                                    <select name="shop_name" required>
                                                        <option value="" disabled selected>Choose shop</option>
                                                        <?php foreach($shop as $value){ ?>
                                                        <option value="<?php echo $value['id'] ?>"><?php echo $value['shop_name'] ?></option>
                                                        <?php } ?>
                                                        
                                                    </select>
                                                    <label>Shop Name</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                            <div class="input-field col s12">
                                                    <select name="cat_name" required>
                                                        <option value="" disabled selected>Choose category</option>
                                                         <?php foreach($category as $val){ ?>
                                                        <option value="<?php echo $val['category_name'] ?>"><?php echo $val['category_name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <label>Category Name</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <button class="btn cyan waves-effect waves-light right" type="submit" name="action">Submit
                                                            <i class="material-icons right">send</i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                      
                    
                 </div>
                </div>
            </div>
            </div>
      
      
    
      
      

<?php  require_once(APPPATH.'views/footer_new.php'); ?>   
