<?php
include('config.php');
$forms = json_decode(file_get_contents($base_url."api.php?action=bicycle_form"),true);
// print_r($forms);
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<?php
include('includes/head.php');
?>

<body>

	<!-- Start Header Area -->
	<?php
	include('includes/header.php');
	?>

	<!-- start product Area -->
	<section class="owl-carousel active-product-area section_gap" id="results">
		<!-- single product slide -->
		<div class="single-product-slider">
			<div class="container">
				<div class="row">
								<div class="col-lg-8" id="pesquisa_campos">
									<form class="row contact_form" action="bikes.php" method="get">
										<div class="col-md-12">

											<div class="form-group">
												<label>Selecione a Marca:</label>
												</br>
												<select class="form-control" name="brandName" id="brandName" onchange="mudar_types();">
													<?php foreach($forms['brands'] as $brand){?>
														<option value="<?=$brand['brandName']?>"><?=$brand['brandName']?></option>
													<?php }?>
											  </select>
											</div>
											</br></br>
											<div class="form-group" id="modelName_atr">
												<label>Selecione o Modelo:</label>
												</br>
												<select class="form-control" name="modelName" id="modelName" onchange="mudar_anos();">
													<?php foreach($forms['models'] as $model){?>
														<option value="<?=$model['modelName']?>"><?=$model['modelName']?></option>
													<?php }?>
											  </select>
											</div>
											<div class="form-group" id="last_year_fab_atr">
												<label>Ano:</label>
												<input type="hidden" class="form-control" name="page" value="1">
												</br>
												<select class="form-control" name="last_year_fab" id="last_year_fab">
											    <option value="2020">2020</option>
											    <option value="2019">2019</option>
											    <option value="2018">2018</option>
											    <option value="2017">2017</option>
													<option value="2016">2016</option>
													<option value="2015">2015</option>
											  </select>
											</div>
											</br></br>
										</div>
										<div class="col-md-12 text-center">
											<input type="submit" value="Começar" class="primary-btn" id="botao-bike">
										</div>
									</form>
								</div>
					</div>
			</div>
		</div>
		<!-- single product slide -->
	</section>
	<!-- end product Area -->

	<!-- Start exclusive deal Area -->


	<!-- start footer Area -->
	<?php
	include('includes/footer.php');
	?>
	<!-- End footer Area -->
	<script>
	function mudar_anos(){
		var brand = document.getElementById('brandName').value;
		var model = document.getElementById('modelName').value;
		$.ajax({url: "api.php?action=mudar_anos&model="+model+"&brand="+brand,
		success: function(result){
				$('#last_year_fab_atr').html(result);
				// alert(result);
		 }});
	}
	function mudar_types(){
		var brand = document.getElementById('brandName').value;
		var year = document.getElementById('last_year_fab').value;
		$.ajax({url: "api.php?action=mudar_types&brand="+brand+"&year="+year,
		success: function(result){
				$('#modelName_atr').html(result);
				mudar_anos();
				// alert(result);
		 }});
	}
	</script>
	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/countdown.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<!--gmaps Js-->
	<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script> -->
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>
