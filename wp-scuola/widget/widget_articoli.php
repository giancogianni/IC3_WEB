<?php
/****************************************************************************
  Widget Articoli
	    Titolo:	($title) Testo che viene visualizzato in Testa all'elenco degli articoli estratti
		Categoria Blocco Sinistra:  ($catprimoblocco) ID della categoria del blocco di Sinistra
		Categoria Blocco Destra:  ($catsecondoblocco) ID della categoria del blocco di Destra
		Numero di Elementi: ($numelementi) Numero di elementi da visualizzare
		Visualizza Link Leggi Tutto: ($linkLT) Flag che indica se deve essere visualizzato il link Leggi Tutto
*/     
    class Articoli extends WP_Widget {

        function __construct() {
			parent::__construct( false, 'Scuola Articoli',
				array('classname' => 'Articoli',
				    'description' => __('Blocco Articoli su due colonne con due categoria','wpscuola')) );
 	      add_action( 'save_post', 		[$this, 'flush_widget_cache'] );
	      add_action( 'deleted_post', 	[$this, 'flush_widget_cache'] );
	      add_action( 'switch_theme', 	[$this, 'flush_widget_cache'] );
       }

        function widget( $args, $instance ) {
	       $cache = [];
	        if ( ! $this->is_preview() ) {
	            $cache = wp_cache_get( 'widget_posts', 'widget' );
	        }

	        if ( ! is_array( $cache ) ) {
	            $cache = [];
	        }

	        if ( ! isset( $args['widget_id'] ) ) {
	            $args['widget_id'] = $this->id;
	        }

	        if ( isset( $cache[ $args['widget_id'] ] ) ) {
	            echo $cache[ $args['widget_id'] ];
	            return;
	        }

	        ob_start();
	        
	        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Articoli su due colonne','wpscuola' );
        	/** This filter is documented in wp-includes/default-widgets.php */
        	$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
 			$catprimoblocco=isset($instance['catprimoblocco'])?$instance['catprimoblocco']:"";
 			$catsecondoblocco=isset($instance['catsecondoblocco'])?$instance['catsecondoblocco']:"";
 			$numelementi=isset($instance['numelementi'])?$instance['numelementi']:"";
 			$linkLT=isset($instance['leggitutto'])?$instance['leggitutto']:"";
 			$VisImgEv=isset($instance['immagineevidenza'])?$instance['immagineevidenza']:"";

			$TitoloPB=isset($instance['titolosx'])?$instance['titolosx']:"";
			$Catargs = array( 'cat' => $catprimoblocco,
				   'posts_per_page'  => $numelementi,
				   'post_status' => (is_user_logged_in()? array('publish','private'):'publish'));
			$ArticoliPB = get_posts( $Catargs );	   
			$TitoloSB=isset($instance['titolodx'])?$instance['titolodx']:"";
			$Catargs = array( 'cat' => $catsecondoblocco,
				   'posts_per_page'  => $numelementi,
				   'post_status' => (is_user_logged_in()? array('publish','private'):'publish'));
			$ArticoliSB = get_posts( $Catargs );	 
?>
 <section id="art_<?php echo $args['widget_id'];?>"  class="home-widget container">
		<div class="it-header-block">
	    	<div class="it-header-block-title">
<?php 
            echo $args['before_widget'];
            if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            } ?>   
	    	</div>
		</div>
		<div class="row">
			<div class="col-lg-6 col-12 pl-2">
			 <div class="shadow">
				<h3 class="my-bg-primary p-3 text-white"><?php echo $TitoloPB;?></h3>	  
				<div class="it-list-wrapper">
					<ul class="it-list">
<?php	foreach($ArticoliPB as $Articolo){	?>
					    <li>
					      <a href="<?php echo get_permalink($Articolo->ID);?>">
				     <?php if($VisImgEv!=1) :?>
				     	    <div class="it-thumb">
		             <?php   echo scuola_get_thumbnail($Articolo->ID);?>					          
					        </div>
				     <?php endif;?>
				        	<div class="it-right-zone  border-0">
				        		<span class="text"><?php echo $Articolo->post_title;?></span>
				        		<span class="it-multiple">
				        			<span class="metadata metadatasmall"><i class="far fa-calendar-alt"></i> <?php echo date_i18n( get_option( 'date_format' ), strtotime($Articolo->post_date) );?></span>
									<span class="metadata metadatasmall"><i class="fas fa-user-edit"></i> <?php echo get_the_author_meta('display_name', $Articolo->post_author);?></span>
								</span>
							</div>
					      </a>
					    </li>    	
<?php			} 
				if($linkLT){?>
						<li>
							<div class="it-card-footer">
		          				<a class="read-more" href="<?php echo get_category_link($catprimoblocco);?>">
		              				<span class="text"><i class="fas fa-link p-1"></i> Leggi tutto</span>
		            			</a>
		          			</div>
						</li>
<?php	}?>					</ul>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-12 pl-2">
			 <div class="shadow">
				<h3 class="my-bg-primary p-3 text-white"><?php echo $TitoloSB;?></h3>	  
				<div class="it-list-wrapper">
					<ul class="it-list">
<?php	foreach($ArticoliSB as $Articolo){	?>
					    <li>
					      <a href="<?php echo get_permalink($Articolo->ID);?>">
					 <?php if($VisImgEv!=1) :?>				          	
					 		<div class="it-thumb">
		             <?php   echo scuola_get_thumbnail($Articolo->ID);?>    
					        </div>
					 <?php endif;?>
				        	<div class="it-right-zone  border-0">
				        		<span class="text"><?php echo $Articolo->post_title;?></span>
				        		<span class="it-multiple">
				        			<span class="metadata metadatasmall"><i class="far fa-calendar-alt"></i> <?php echo date_i18n( get_option( 'date_format' ), strtotime($Articolo->post_date) );?></span>
									<span class="metadata metadatasmall"><i class="fas fa-user-edit"></i> <?php echo get_the_author_meta('display_name', $Articolo->post_author);?></span>
								</span>
							</div>
					      </a>
					    </li>    	
<?php			} 
				if($linkLT){?>
						<li>
							<div class="it-card-footer">
		          				<a class="read-more" href="<?php echo get_category_link($catprimoblocco);?>">
		              				<span class="text"><i class="fas fa-link p-1"></i> Leggi tutto</span>
		            			</a>
		          			</div>
						</li>
<?php	}?>			</ul>
					</div>
				</div>
		 	</div>
		</div>
<?php   echo $args['after_widget']; ?>
</section>
<?php                   
	wp_reset_postdata();

    if ( ! $this->is_preview() ) {
        $cache[ $args['widget_id'] ] = ob_get_flush();
        wp_cache_set( 'widget_posts', $cache, 'widget' );
    } else {
        ob_end_flush();
    }
}
        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['catprimoblocco']=strip_tags($new_instance['catprimoblocco']);   
            $instance['catsecondoblocco']=strip_tags($new_instance['catsecondoblocco']);   
            $instance['numelementi']=strip_tags($new_instance['numelementi']);   
			$instance['leggitutto']=strip_tags($new_instance['leggitutto']);
			$instance['immagineevidenza']=strip_tags($new_instance['immagineevidenza']);		
			$instance['titolosx']=strip_tags($new_instance['titolosx']);
			$instance['titolodx']=strip_tags($new_instance['titolodx']);
           return $instance;
        }
	    public function flush_widget_cache() 
	    {
	        wp_cache_delete('widget_posts', 'widget');
	    }
        function form( $instance ) {
           // $instance = wp_parse_args( (array) $instance, array( ) ); 
            $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Comunicazioni', 'text_domain' );
 			$catprimoblocco=isset($instance['catprimoblocco'])?$instance['catprimoblocco']:0;
 			$catsecondoblocco=isset($instance['catsecondoblocco'])?$instance['catsecondoblocco']:0;
            $numelementi=isset($instance['numelementi'])?$instance['numelementi']:5;
            $titolosx=isset($instance['titolosx'])?$instance['titolosx']:"";
            $titolodx=isset($instance['titolodx'])?$instance['titolodx']:"";
?>           

           <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>">Titolo Sezione:</label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <p>
             <label for="<?php echo $this->get_field_id( 'numelementi' ); ?>">N&ordm; elementi da visualizzare:</label>
            <input type="number" min="1" max="10" id="<?php echo $this->get_field_id( 'numelementi' ); ?>" name="<?php echo $this->get_field_name( 'numelementi' ); ?>" value="<?php echo $numelementi; ?>" />
        </p>
     <div class="Servizi">
        <h3>Blocco di sinistra</h3>
     	<label for="<?php echo $this->get_field_id( 'titolosx' ); ?>">Titolo:</label>
    	<input type="text" id="<?php echo $this->get_field_id( 'titolosx' ); ?>" name="<?php echo $this->get_field_name( 'titolosx' ); ?>" value="<?php echo $titolosx; ?>" ><br />
		<input type="hidden" name="catprimoblocco" id="catprimoblocco" value="-1">
        <label for="<?php echo $this->get_field_id( 'catprimoblocco' ); ?>">Categoria:</label>
<?php  
	$args = array(
	'show_option_none'   => 'Non Visualizzare la colonna',
	'show_option_all'    => 'Tutte le comunicazioni',
	'option_none_value'  => '-1',
	'orderby'            => 'name',
	'order'              => 'ASC',
	'show_count'         => 0,
	'hide_empty'         => FALSE,
	'child_of'           => 0,
	'echo'               => TRUE,
	'selected'           => $catprimoblocco,
	'name'               => $this->get_field_name('catprimoblocco'),
	'id'                 => $this->get_field_id('catprimoblocco'),
	'class'              => '',);
	wp_dropdown_categories( $args );
?>
     </div>
     <div class="Servizi">
    	<h3>Blocco di destra</h3>
    	<label for="<?php echo $this->get_field_id( 'titolodx' ); ?>">Titolo:</label>
    	<input type="text"  id="<?php echo $this->get_field_id( 'titolodx' ); ?>" name="<?php echo $this->get_field_name( 'titolodx' ); ?>" value="<?php echo $titolodx; ?>"><br />
    	<label for="<?php echo $this->get_field_id( 'catsecondoblocco' ); ?>">Categoria:</label>
<?php  
	$args = array(
	'show_option_none'   => 'Non Visualizzare la colonna',
	'show_option_all'    => 'Tutte le comunicazioni',
	'option_none_value'  => '-1',
	'orderby'            => 'name',
	'order'              => 'ASC',
	'show_count'         => 0,
	'hide_empty'         => FALSE,
	'child_of'           => 0,
	'echo'               => TRUE,
	'selected'           => $catsecondoblocco,
	'name'               => $this->get_field_name('catsecondoblocco'),
	'id'                 => $this->get_field_id('catsecondoblocco'),
	'class'              => '',);
	wp_dropdown_categories( $args );
?>
     </div>
     <div class="Servizi">
    	<h3>Visualizza Immagine in Evidenza</h3>
    	<label for="<?php echo $this->get_field_id( 'immagineevidenza' ); ?>">Attiva:</label>
    	<input type="checkbox" id="<?php echo $this->get_field_id('immagineevidenza'); ?>" name="<?php echo $this->get_field_name('immagineevidenza'); ?>" value="1" <?php echo ($instance['immagineevidenza'] == '1') ? 'checked="checked"':''; ?>/>
    </div>
    <div class="Servizi">
    	<h3>Link Leggi Tutto</h3>
    	<label for="<?php echo $this->get_field_id( 'leggitutto' ); ?>">Attiva:</label>
    	<input type="checkbox" id="<?php echo $this->get_field_id('leggitutto'); ?>" name="<?php echo $this->get_field_name('leggitutto'); ?>" value="1" <?php echo ($instance['leggitutto'] == '1') ? 'checked="checked"':''; ?>/>
    </div>
      <?php
		}
     }		
