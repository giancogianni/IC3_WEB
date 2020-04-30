<?php 
/**
 * @wordpress-theme
 * Theme Name:       	WPScuola
 * File Name:			search.php
 * Description:       	Template della pagina di ricerca con sidebar di destra che riporta l'archivio degli articoli trovati per periodo
 * Author:            	Ignazio Scimone
 * Author URI:        	http://www.eduva.org/
 * License: 			AGPL-3.0
 * License URI: 		https://opensource.org/licenses/AGPL-3.0
 * Text Domain:       	wpscuola
*/

get_header();

function sum_ArtAnno($ArchivioDate,$Anno){
	$TotPost=0;
	foreach($ArchivioDate[$Anno] as $Mese=>$CountArt)
		$TotPost=$TotPost+$CountArt;
	return $TotPost;
}
function my_get_archives()
{
	global $query_string, $mesi;
	wp_parse_str( $query_string, $search_query );
	unset($search_query["year"]);
	unset($search_query["monthnum"]);
	$TestoSearch=$search_query["s"];
	$search_query["nopaging"]=true;
	$search_query["orderby"]="date";
	$search = new WP_Query( $search_query );
	$Posts=$search->get_posts();
	$ArchivioDate=array();
	foreach($Posts as $Post){
		$Anno=date("Y",strtotime($Post->post_date));
		$Mese=(int) date("m",strtotime($Post->post_date));
		if(isset($ArchivioDate[$Anno][$Mese])){
			$ArchivioDate[$Anno][$Mese]=$ArchivioDate[$Anno][$Mese]+1;
		}else{
			$ArchivioDate[$Anno][$Mese]=1;
		}		
	}
	wp_reset_query();
	wp_reset_postdata();

	$Result="";
	$CAnno=0;
	foreach ($ArchivioDate as $Anno=>$Data ) {
		foreach($Data as $Mese=>$CountArt){
			if ($CAnno!=$Anno){
				if ($CAnno!=0) {
					$Result.='			</ul>
					</li>';
				}
				$Result.='
				<li>
						<div class="row">
							<div class="col-9 p-0 mb-0">
								<a class="list-item large" href="'.esc_url(home_url('/')).$Anno.'/?s='.$TestoSearch.'">
									<span class="m-0">'.$Anno.'</span>
								</a>						
							</div>
							<div class="col-1 m-0">
								<span class="badge badge-pill badge-primary text-white">'.sum_ArtAnno($ArchivioDate,$Anno).'</span>
							</div>
							<div class="col-2 p-0">
							<a class="list-item" href="'."#S".$Anno.'M'.$Mese.'" data-toggle="collapse" aria-expanded="false" aria-controls="S'.$Anno.'M'.$Mese.'">
							<i class="fas fa-angle-down fa-2x"></i>
								</a>
							</div>
						</div>

						<ul class="link-sublist collapse" id="S'.$Anno.'M'.$Mese.'">';
				$CAnno=$Anno;
			}
			$Result.='
					<li>
						<a class="list-item subele pl-0" href="'.esc_url(home_url('/')).$Anno."/".$Mese.'/?s='.$TestoSearch.'">
							<div class="row">
								<div class="col-10">	
									<span class="m-0">'.$mesi[$Mese].'</span>
								</div>
								<div class="col-2 m-0">
									<span class="badge badge-pill badge-primary text-white">'.$CountArt.'</span>
								</div>
							</div>
						</a>
					</li>';	
		}		  			
	}
	return $Result;
}

if ( have_posts() And (strlen( trim(get_search_query()))) >= 1){
	$SearchOk=TRUE;
}else{
	$SearchOk=FALSE;
}
?>
<section id="content" role="main" class="container-fluid">
   <div class="container-fluid affix-parent">
		<div class="row">
	      	<div class="col d-flex justify-content-center pagesearch">
	           <header class="header mt-5">
	                  <h3 class='entry-title'><?php printf( __(  'Inserisci un termine per la ricerca: ', 'wpscuola' ) ); ?></h3>
	            <?php get_search_form(); ?>
	            </header>
	        </div>
		</div>
<?php if ( !$SearchOk ) : ?>
      	<div class="row  mb-5">
 			<div class="col d-flex justify-content-center">
           		<div class="callout callout-highlight danger">
  					<div class="callout-title">
  						<i class="fas fa-info-circle"></i> <?php _e( 'Attenzione', 'wpscuola' ); ?>
  					</div>
  					<p><?php _e( 'Non sono stati trovati contenuti con i parametri indicati', 'wpscuola' ); ?></p>
				</div>
			</div>
<?php else:?>
	</div>
    <div class="row">
 		  <div class="col-12 col-lg-9">
		  	<div class="container">
                 <hr class="mt-5">
 		  <?php	while ( have_posts() ) : the_post(); 
 		  			get_template_part( 'template-parts/section', 'art_list-item' ); 
 		  		endwhile; 
		  		wp_reset_postdata();?>
		  	</div>
<?php get_template_part( 'template-parts/pagin' ); ?>
		  </div>
		  <div class="col-12 col-lg-3">
			  <div class="link-list-wrapper p-1">
					<div class="link-list-wrapper shadow p-1 mt-5">
						<h4 class="TitoloArchivio"><?php _e( 'Data di Pubblicazione', 'wpscuola' ); ?></h4>
						<ul class="link-list" id="ListaCategorieData">				
							<?php echo my_get_archives(); ?>
						</ul>
					</div>
				</div>
		  </div>
	</div>
<?php endif;?>
  </div>
</section>

<?php get_footer(); ?>