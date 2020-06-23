<?php
include('config.php');
$pagina_atual = intval($_GET['page']);
$endereco = $base_url."api.php?action=bicycle_pesquisa&brandName=".str_replace(' ','+',$_GET['brandName']);
// echo $endereco;
$bikes = json_decode(file_get_contents($endereco),true);
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
				<div class="row justify-content-center">
					<div class="col-lg-6 text-center">
						<div class="section-title" style="color:#27B67C;">
							<h1 style="color:gray;"><a style="color:#27B67C;"><?=count($bikes)?> </a><a style="color:gray;">Resultados para sua busca</a></h1>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- single product -->
					<?php foreach($bikes as $bike){
						if(strlen($bike['image'])==0){
							$img = json_decode(file_get_contents($base_url."api.php?action=bicycle_details&id_bicycle=".$bike['id']),true);
						}else{
							$img['imageDefault'] = $bike['image'];
						}
						if(isset($img['imageDefault'])){
						?>
						<div class="col-lg-3 col-md-6">
							<div class="single-product">
								<img class="img-fluid" src="<?=$img['imageDefault']?>" alt="" width="360" height="215">
								<div class="product-details">
									<h6><?=$bike['name']?></h6>
									<div class="prd-bottom">
										<a href="bike.php?id=<?=$bike['id']?>" class="primary-btn" id="botao-bike">Ver Valor</a>
									</div>
								</div>
							</div>
						</div>
					<?php
				}
			} ?>
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
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>
