<?php
include('config.php');
$bike = json_decode(file_get_contents($base_url."api.php?action=bicycle_details&id_bicycle=".$_GET['id']),true);
// echo $base_url."api.php?action=bicycle_desvalorizacao&id_bicycle=".$_GET['id'];
$bike_desvalorizacao = json_decode(file_get_contents($base_url."api.php?action=bicycle_desvalorizacao&id_bicycle=".$_GET['id']),true);
$bike_year_d = intval(date('Y'))-$bike_desvalorizacao['last_year_fab'];
if($bike_year_d>5){
	$bike_year_d = 5;
}
$cotacao = $bike_desvalorizacao['Ano '.$bike_year_d];
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
	<div class="product_image_area">
			<div class="container" id="topo_graphs">
				<div class="row s_product_inner">
					<div class="col-lg-3">
						<img class="img-fluid" src="img/Assets-04.png" alt="">
						<div class="primary-switch">
							<input type="checkbox" id="default-switch4" onchange="valor_bike(4,'default-switch4',this.checked);">
							<label for="default-switch4"></label>
						</div>
					</div>
					<div class="col-lg-3">
						<img class="img-fluid" src="img/Assets-05.png" alt="">
						<div class="primary-switch">
							<input type="checkbox" id="default-switch5" onchange="valor_bike(5,'default-switch5',this.checked);">
							<label for="default-switch5"></label>
						</div>
					</div>
					<div class="col-lg-3">
						<img class="img-fluid" src="img/Assets-06.png" alt="">
						<div class="primary-switch">
							<input type="checkbox" id="default-switch6" onchange="valor_bike(6,'default-switch6',this.checked);">
							<label for="default-switch6"></label>
						</div>
					</div>
					<div class="col-lg-3">
						<img class="img-fluid" src="img/Assets-07.png" alt="">
						<div class="primary-switch">
							<input type="checkbox" id="default-switch7" onchange="valor_bike(7,'default-switch7',this.checked);">
							<label for="default-switch7"></label>
						</div>
					</div>
				</div>
			</div>
			<div class="container" style="padding-top:5%;">
				<div class="row s_product_inner">
					<div class="col-lg-6">
						<img class="img-fluid" src="<?=$bike['image']?>" alt="">
					</div>
					<div class="col-lg-5 offset-lg-1">
						<div class="s_product_text">
							<!-- <h3 style="color:#27B67C;"><?=$bike['brandName']?></h3> -->
							<h3 style="color:#27B67C;"><?=$bike['name']?></h3>
							<!-- <h3 style="color:#27B67C;">Preço de Loja: R$ <?=number_format($bike_desvalorizacao['Preco Brasil'],2,",",".")?></h3> -->
							<!-- <ul class="list">
								<li><a><span>Marca:</span> <?=$bike['brandName']?></a></li>
								<li><a><span>Tipo:</span> <?=$bike['typeName']?></a></li>
								<li><a><span>Model:</span> <?=$bike['modelName']?></a></li>
							</ul> -->
							</br></br>
							<ul class="list">
								<h3 style="color:#27B67C;" id="price_value"></h3>
							</ul>
							<!-- <p><?=$bike['description']?></p> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- end product Area -->


	<section class="product_description_area">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-6 col-sm-6">
					<h1 class="text-center">Detalhes da Bicicleta:</h1>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
							<div class="table-responsive">
								<table class="table">
									<tbody>
										<?php foreach($bike['components'] as $component){?>
											<tr>
												<td>
													<h5><?=$component['componentName']?></h5>
												</td>
												<td>
													<h5><?=$component['componentValue']?></h5>
												</td>
											</tr>
										<?php }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<?php if(count($bike['additionalYears'])>0){?>
					<div class="col-lg-12 col-md-6 col-sm-6">
						<h1 class="text-center" style="padding:10% 0;">Esse modelo também foi fabricado em:</h1>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4">
						<?php foreach($bike['additionalYears'] as $year){?>
							<img class="img-fluid" src="<?=$year['image']?>" alt="">
							<label><?=$year['yearName']?></label>
						<?php }?>
					</div>
				<?php }?>
			</div>
		</div>
	</section>

	<!-- Start exclusive deal Area -->


	<!-- start footer Area -->
	<?php
	include('includes/footer.php');
	?>
	<!-- End footer Area -->

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
	<script>
	function cotacao(cotacao_id,cotacao_ele){
		var cotacao =
		{
			7:'<?=number_format(($cotacao*0.65),2,",",".")?>',
			6:'<?=number_format(($cotacao*0.9),2,",",".")?>',
			5:'<?=number_format(($cotacao*1),2,",",".")?>',
			4:'<?=number_format(($cotacao*1.05),2,",",".")?>'
		};
		document.getElementById("price_value").innerHTML = 'Valor: R$ '+cotacao[cotacao_id];
	}
	function valor_bike(cotacao_id,cotacao_ele,checked){
		document.getElementById("default-switch4").checked = false;
		document.getElementById("default-switch5").checked = false;
		document.getElementById("default-switch6").checked = false;
		document.getElementById("default-switch7").checked = false;
		document.getElementById(cotacao_ele).checked = true;
		if(checked==true){
			document.getElementById("price_value").style.display = "block";
		}
		// else{
		// 	document.getElementById("price_value").style.display = "none";
		// }
		cotacao(cotacao_id,cotacao_ele);
	}
	valor_bike(6,'default-switch6',this.checked);
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>
