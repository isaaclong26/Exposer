<?php
/**
 * Template Name: Project Gallery
 *
 */
get_header(); 
$project_gallery_filter_form = new WP_Advanced_Search('project-gallery-filter-form');

if (have_posts()) :
	while (have_posts()) : 
		the_post();
?>

<div class="container">

	<div class="row">
		<?php 
		if (function_exists('yoast_breadcrumb')) { 
			yoast_breadcrumb('<p id="breadcrumbs">','</p>'); 
		} ?>
	</div>
	<!-- breadcrumbs -->

	<div class="row col">
		<h1><?php the_title(); ?></h1>
	</div>
	<!--page title-->
	
	<div class="content">
		<div class="row">
			<div class="col">
				<p>With over 30,000 projects delivered since 1998, Solar Innovations<sup>®</sup> is proud to showcase our wide array of products through this searchable photo gallery. While there are many completed projects not shown in this gallery, we are expanding our library of over 10,000 photos each month to provide our customers with a valuable resource to inspire and build their next project.</p>
			</div>
		</div>

		<section class="flex row">
			<?php
			if (!isset($_GET['wpas_submit']) && !isset($_GET['project_id'])) : //Display Categories
				if (!isset($_GET['product'])) : //Show All Product Categories
					$args = array(  'type'=> 'category',
									'child_of' => '',
									'parent' => 37,
									'orderby' => 'name',
									'order' => 'ASC',
									'hide_empty' => 0,
									'hierarchical' => 1,
									'exclude' => '',
									'include' => '',
									'number' => '',
									'taxonomy' => 'category',
									'pad_counts' => false,
								);
					$categories = get_categories($args);
			?>
			
			<!-- Start Photo Gallery Main Categories Page -->
			
			<div class="col-md-12 col-lg-3">
				<div class="sidebar col">
					<?php $project_gallery_filter_form->the_form(); ?>
				</div>
			</div>
			<!--sidebar-->
				
			<div class="col-md-12 col-lg-9">
				<div class="row">	
				
					<?php
					foreach ($categories as $category) :
					$image = get_field('image', 'category_'.$category->cat_ID);
					?>

					<div class="project-grid-item col-sm-12 col-md-4 col-lg-3">	
						<a href="<?=get_page_link() . '?product=' . $category->cat_ID?>" class="port-img">
							<?php if (!empty($image)) : ?>
								<img src="<?=$image['sizes']['medium']?>" height="177" width="236">
							<?php else : ?> 
								<img src="/wp-content/uploads/2015/08/placeholder.png" height="177" width="236">
							<?php endif; ?>
							<span class="title"><?=$category->name?></span>
						</a>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<!--main-->
			<!-- End Photo Gallery Main Categories Page -->
				
			<?php 
				else: 
					$parent = new WP_Query( 'cat='.$_GET['product'] );
					$args = array(  'type'=> 'category',
									'child_of' => '',
									'parent' => $_GET['product'],
									'orderby' => 'name',
									'order' => 'ASC',
									'hide_empty' => 0,
									'hierarchical' => 1,
									'exclude' => '',
									'include' => '',
									'number' => '',
									'taxonomy' => 'category',
									'pad_counts' => false 
								);
					
					$categories = get_categories($args);
			?>
				
			<!-- Start Photo Gallery Subcategories Page -->
			<div class="col-md-12 col-lg-3">
				<div class="sidebar col">
					<?php $project_gallery_filter_form->the_form(); ?>
				</div>
			</div>
			<!--sidebar-->
			
			<div class="col-md-12 col-lg-9">
				<div class="row">
					<div class="col">
						<h2><?=get_cat_name($_GET['product'])?></h2>
						<p><?=category_description($_GET['product'])?></p>
					</div>
				</div>
				
				<div class="row">
					<?php
					foreach ($categories as $category) :
						$image = get_field('image', 'category_'.$category->cat_ID);
					?>
				
					<div class="project-grid-item col-sm-12 col-md-4 col-lg-3">
						
						<a href="<?=get_page_link() . '?search_query=&meta_products=' .$category->name . '&meta_application=0&meta_interior_finish=0&meta_exterior_finish=0&meta_architectural_type=0&meta_architectural_enhancements=0&meta_glazing=0&meta_accessories=0&meta_zones=0&wpas_id=project-gallery-filter-form&wpas_submit=1'?>">
							<?php if (!empty($image)) : ?>
								<img src="<?=$image['sizes']['medium']?>" height="169" width="245">
							<?php else : ?>
								<img src="/wp-content/uploads/2015/08/placeholder.png" height="169" width="245">
							<?php endif; ?>
							<span class="title"><?=$category->name?></span>
						</a>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<!-- End Photo Gallery Subcategories Page -->
						
			<?php 
				endif;
				elseif (!isset($_GET['project_id'])) : //Display WPAS Results
				$project_gallery_filter_form = new WP_Advanced_Search('project-gallery-filter-form');
				$query = $project_gallery_filter_form->query();
			?>	
			
			<!-- Start Photo Gallery Results Page -->
			
			<div class="col-md-12 col-lg-3">
				<div class="sidebar col">
					<?php $project_gallery_filter_form->the_form(); ?>
				</div>
			</div>
			<!--sidebar-->
		
			<div class="col-md-12 col-lg-9">
			
				<?php if ($query->have_posts()) :?>
				
					<div class="row">
					
						<?php 
						while ($query->have_posts()) : 
							$query->the_post();
							global $post; 
						?>
						
						<div class="project-grid-item col-sm-12 col-md-4 col-lg-3">
							<a href="?project_id=<?=$post->ID?>">
							
								<?php
								$rows = get_field('images'); // get all the rows
								$first_row = $rows[0]; // get the first row
								$first_row_image = $first_row['image']; // get the sub field value 
								$photo = wp_get_attachment_image_src($first_row_image, 'full');
								?>
								
								<img src="<?php echo $first_row_image; ?>" alt="" />
								<span class="title"><?=the_title()?></span>
							</a>
						</div>
						
						<?php endwhile; ?>
						
					</div>
					
					<div class="displaying">
					
						<div class="results">
						<?php
							echo '<span>Displaying results </span>'; 
							echo (intval($query->found_posts) >= intval($query->query['posts_per_page'])) ? $project_gallery_filter_form->results_range() : '1-' . $query->found_posts;
							echo '<span> of </span>' . '<span>' . $query->found_posts . '</span>';
						?>
						</div>

						<?php
							$project_gallery_filter_form->pagination(array('prev_text' => '«','next_text' => '»'));
						?>
					</div>
				
				<!-- If there are 0 results -->
				<?php else: ?>
				
					<?php // Related Projects if too many filters return 0 results
					$cats = $_GET['meta_products']; //gets the category name
					$cat = preg_replace(array("/\//", '/\s+/'), array('_', '_'),$cats); //replaces spaces and forward slashes with underscores to match the slug
	
					$args = array(  'category_name' => $cat, //parameter: category name is equal to $cat variable
									'category__not_in' => array(0), //excludes category 0
									'post_type' => 'project', //post type must be 'project'
									'post_status' => 'publish', //make sure the post is published
									'posts_per_page' => '8', //display 8 posts on the page
									'orderby' => 'rand', //order the posts randomly
								);
								
					$related = new WP_Query($args); //delcare $related as the query
					
					if ($related->have_posts() && $cat != '0') : //if the query has results, continue
					?>

						<div class="row col">
							<h2 class="w-100">No Projects Found!</h2>
							<p>Try using less filters to broaden your search.</p>
							<h4 class="w-100">Other Projects You Might Like</h4>
							<p>These projects all include the product category: <b><?php echo $cats ?></b></p>
						</div>
						
						<div class="row">							

							<?php
							while ($related->have_posts()) : 
								$related->the_post();
								global $post;
							?>
							
							<div class="project-grid-item col-sm-12 col-md-4 col-lg-3">
								<a href="?project_id=<?=$post->ID?>" class="port-img">
									<?php

									$rows = get_field('images'); // get all the rows
									$first_row = $rows[0]; // get the first row
									$first_row_image = $first_row['image']; // get the sub field value
									$photo = wp_get_attachment_image_src($first_row_image, 'full');
									?>
									<img src="<?php echo $first_row_image; ?>" alt="test" />
									<span class="title"><?=the_title()?></span>
								</a>
							</div>
							
							<?php endwhile; ?>

						</div>
					<?php else: ?>
						<div class="row col">
							<h2 class="w-100">No Projects Found!</h2>
							<p>Try using less filters to broaden your search.</p>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<!--main-->
			<!-- Photo Gallery Results Page -->

			<?php 
			else : //Display Single Product Details
				$args = array('p' => $_GET['project_id'],'post_type' => 'project');
				$query = new WP_Query($args);
				if ($query->have_posts()) : 
					while ($query->have_posts()) : 
						$query->the_post();
						global $post; 
			?>

			<!-- Start Individual Project Page Template-->
			<div class="project-page container">
				<div class="project-wrap">
				
					<div class="row col">
						<a class="project-page-back" onclick="history.go(-1);"><span style="color:#8cbb8e;">◄</span> Back to Search Results</a>
						<span class="project-page-id">Project: <?=the_title()?></span>
					</div>
					
					<div id="project-gallery" class="carousel slide" data-ride="carousel" data-interval="false">
						<?php if (have_rows('images')) : ?>
						
							<!-- Indicators -->
							<ul class="carousel-indicators">
								<?php 
								$count = 0;
								while (have_rows('images')): 
									the_row();
								?>
							
								<li data-target="#project-gallery" data-slide-to="<?php echo $count; ?>" class="<?php $count++; if($count == 1) { echo ' active'; } ?>"></li>
								
								<?php endwhile;	?>
							</ul>

							<!-- The slideshow -->
							<div class="carousel-inner">

								<?php
								$count = 0;
								while (have_rows('images')): 
									the_row();
									$img = get_sub_field('image');
									$caption = get_sub_field('caption');
								?>

								<div class="carousel-item <?php $count++; if($count == 1) { echo ' active'; } ?>">
									<img src="<?=$img?>" alt="<?=$caption?>">
								</div>

								<?php endwhile;	?>
							</div>
							
						<?php endif; ?>

						<!-- Left and right controls -->
						<a class="carousel-control-prev" href="#project-gallery" data-slide="prev">
							<span class="carousel-control-prev-icon"></span>
						</a>
						<a class="carousel-control-next" href="#project-gallery" data-slide="next">
							<span class="carousel-control-next-icon"></span>
						</a>
						
					</div>
					
					<div class="info-holder mb-3">
						<span id="info-holder-desc">Description</span>
						<p><?=the_content()?></p>
					</div>
					
					<div class="specifications-wrap col mb-3">
						
						<div class="row">
							<h2>Project Specifications</h2>
						</div>
						
						<?php 
							if ($acc = get_field('accessories')) :
							$ia = 0;
							$string = '';
							if (is_array($acc)) :
								foreach ($acc as $a) :
									$string .= $a . ', ';
									$ia++;
								endforeach;
								$string = rtrim($string, ', ');
							else :
								$string = $acc;
							endif;
							$sa = ($ia > 1) ? 's' : '';
						?>
							<div class="row small-collapse proj-spec-row">
								<div class="col-sm-12 col-md-4 columns">
									<p class="info-text-1">Accessories</p>
								</div>
								<div class="col-sm-12 col-md-8 columns">
									<p class="info-text-2"><?=' ' . $string?></p>
								</div>
							</div>
						<?php 
						endif;						

							if ($app = get_field('application')) :
							$ia = 0;
							$string = '';
							if (is_array($app)) :
								foreach ($app as $a) :
									$string .= $a . ', ';
									$ia++;
								endforeach;
								$string = rtrim($string, ', ');
							else :
								$string = $app;
							endif;
							$sa = ($ia > 1) ? 's' : '';
						?>
							<div class="row small-collapse proj-spec-row">
								<div class="col-sm-12 col-md-4 columns">
									<p class="info-text-1">Application<?=$sa?></p>
								</div>
								<div class="col-sm-12 col-md-8 columns">
									<p class="info-text-2"><?=' ' . $string?></p>
								</div>
							</div>
						<?php 
						endif;
						
							if ($arch_enh = get_field('architectural_enhancements')) :
							$ia = 0;
							$string = '';
							if (is_array($arch_enh)) :
								foreach ($arch_enh as $a) :
									$string .= $a . ', ';
									$ia++;
								endforeach;
								$string = rtrim($string, ', ');
							else :
								$string = $arch_enh;
							endif;
							$sa = ($ia > 1) ? 's' : '';
						?>
							<div class="row small-collapse proj-spec-row">
								<div class="col-sm-12 col-md-4 columns">
									<p class="info-text-1">Architectural Enhancement<?=$sa?></p>
								</div>
								<div class="col-sm-12 col-md-8 columns">
									<p class="info-text-2"><?=' ' . $string?></p>
								</div>
							</div>
						<?php 
						endif;
						
							if ($arch_type = get_field('architectural_type')) :
							$ia = 0;
							$string = '';
							if (is_array($arch_type)) :
								foreach ($arch_type as $a) :
									$string .= $a . ', ';
									$ia++;
								endforeach;
								$string = rtrim($string, ', ');
							else :
								$string = $arch_type;
							endif;
							$sa = ($ia > 1) ? 's' : '';
						?>
							<div class="row small-collapse proj-spec-row">
								<div class="col-sm-12 col-md-4 columns">
									<p class="info-text-1">Architectural Type<?=$sa?></p>
								</div>
								<div class="col-sm-12 col-md-8 columns">
									<p class="info-text-2"><?=' ' . $string?></p>
								</div>
							</div>
						<?php 
						endif;
							
						if ($ext = get_field('exterior_finish')) :
							$ia = 0;
							$string = '';
							if (is_array($ext)) :
								foreach ($ext as $a) :
									$string .= $a . ', ';
									$ia++;
								endforeach;
								$string = rtrim($string, ', ');
							else :
								$string = $ext;
							endif;
							$sa = ($ia > 1) ? 'es' : '';
						?>
							<div class="row small-collapse proj-spec-row">
								<div class="col-sm-12 col-md-4 columns">
									<p class="info-text-1">Exterior Finish<?=$sa?></p>
								</div>
								<div class="col-sm-12 col-md-8 columns">
									<p class="info-text-2"><?=' ' . $string?></p>
								</div>
							</div>
						<?php 
						endif;
							
						if ($int = get_field('interior_finish')) :
							$string = '';
							$ia = 0;
							if (is_array($int)) :
								foreach ($int as $a) :
									$string .= $a . ', ';
									$ia++;
								endforeach;
								$string = rtrim($string, ', ');
							else : 
								$string = $int;
							endif;
							
							$sa = ($ia > 1) ? 'es' : '';
						?>
							<div class="row small-collapse proj-spec-row">
								<div class="col-sm-12 col-md-4 columns">
									<p class="info-text-1">Interior Finish<?=$sa?></p>
								</div>
								<div class="col-sm-12 col-md-8 columns">
									<p class="info-text-2"><?=' ' . $string?></p>
								</div>
							</div>
						<?php 
						endif;
							
						if ($glz = get_field('glazing')) :
							$string = '';
							$ia = 0;
							if (is_array($glz)) :
								foreach ($glz as $a) :
									$string .= $a . ', ';
									$ia++;
								endforeach;
								$string = rtrim($string, ', ');
							else :
								$string = $glz;
							endif;
							
							$sa = ($ia > 1) ? 's' : '';
						?>
							<div class="row small-collapse proj-spec-row">
								<div class="col-sm-12 col-md-4 columns">
									<p class="info-text-1">Glazing<?=$sa?></p>
								</div>
								<div class="col-sm-12 col-md-8 columns">
									<p class="info-text-2"><?=' ' . $string?></p>
								</div>
							</div>
						<?php
						endif;
							
						if ($prod = get_field('products')) :
							$ia = 0;
							$ib = 0;
							$ic = 0;
							$string = '';
							$stringb = '';
							$stringc = '';
							$stringd = '';
							//project has more than one product
							if (is_array($prod)) :
								foreach ($prod as $a) :
									$string .= $a . ', ';
									$ia++;
									if ($type = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$a))) . '_product_types')) :
										if (is_array($type)) :
											foreach ($type as $b) :
												$stringb .= $b . ', ';
												$ib++;
											endforeach;
										else : 
											$stringb = $type;
										endif;
									endif;
									
									if ($detail = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$a))) . '_product_details')) :
										if (is_array($detail)) :
											foreach ($detail as $c) :
												$stringc .= $c . ', ';
												$ic++;
											endforeach;
										else :
											$stringc = $detail;
										endif;
									endif;
								endforeach;
								$string = rtrim($string, ', ');
								$stringb = rtrim($stringb, ', ');
								$stringc = rtrim($stringc, ', ');
							else : //if project has only one product defined (default)
								$string = $prod;
								
								if ($detail = get_field(strtolower(str_replace(' ','_',str_replace('/ ','',$prod))) . '_product_details')) :
									if (is_array($detail)) :
										foreach ($detail as $c) :
											$stringc .= $c . ', ';
											$ic++;
										endforeach;
									else :
										$stringc = $detail;
									endif;
								endif;
								
								$string = rtrim($string, ', ');
								$stringb = rtrim($stringb, ', ');
								$stringc = rtrim($stringc, ', ');
							endif;
							
							$sa = ($ia > 1) ? 's' : '';
							$sb = ($ib > 1) ? 's' : '';
							$sc = ($ic > 1) ? 's' : '';
							?>
							<div class="row small-collapse proj-spec-row">
								<div class="col-sm-12 col-md-4 columns">
									<p class="info-text-1">Product<?=$sa?></p>
								</div>
								<div class="col-sm-12 col-md-8 columns">
									<p class="info-text-2"><?=' ' . $string?></p>
								</div>
							</div>
							<?php if (strlen($stringc) > 0) : ?>
								<div class="row small-collapse proj-spec-row">
									<div class="col-sm-12 col-md-4 columns">
										<p class="info-text-1">Product Detail<?=$sc?></p>
									</div>
									<div class="col-sm-12 col-md-8 columns">
										<p class="info-text-2"><?=' ' . $stringc?></p>
									</div>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
				
				<?php // Related Projects
				$cats = get_the_category();
				$category__in = array();
				foreach ($cats as $cat) :
					if ($cat->parent == 2)
						$category__in[] = $cat->term_id;
				endforeach;

				$args = array(  'category__in' => $category__in,
								'post_type' => 'project',
								'posts_per_page' => '4',
								'orderby' => 'rand',
								'post__not_in' => array(get_the_ID()),
							);
				$related = new WP_Query($args);
				if ($related->have_posts()) : 
				?>
				
				<div class="related-section">
					<div class="row col">
						<h2>Other Projects You Might Like</h2>
					</div>
					
					<div class="row">
						<?php
						while ($related->have_posts()) : 
							$related->the_post();
							global $post; ?>
						
						<a href="?project_id=<?=$post->ID?>" class="related-project col-lg-3 col-md-4 col-sm-12">
						
							<?php

							$rows = get_field('images'); // get all the rows
							$first_row = $rows[0]; // get the first row
							$first_row_image = $first_row['image']; // get the sub field value 
							$photo = wp_get_attachment_image_src($first_row_image, 'full'); 
							
							?>
							
							<img class="thumbnail" src="<?php echo $first_row_image; ?>" alt="test" />
							<span class="title"><?=the_title()?></span>
						</a>
							
						<?php endwhile; ?>
					</div>
				</div>
				
				<?php endif; ?>
			</div>
			<?php				
						endwhile;
					endif;
				endif; 
				?>
		</section>
	</div>
</div>








<script type="text/javascript">var upload_dir_baseurl = '<?php $upload_dir = wp_upload_dir(); echo $upload_dir['baseurl']; ?>';</script>
<script type="text/javascript">
function toggleProduct(product) {
	jQuery('#wpas-meta_'+product+'_product_types input[type=checkbox], #wpas-meta_'+product+'_product_details input[type=checkbox]').attr('checked', false);
	jQuery('#wpas-meta_'+product+'_product_types, #wpas-meta_'+product+'_product_details').toggle();
}

function uncheckProduct(product) {
	jQuery('#wpas-meta_'+product+'_product_types, #wpas-meta_'+product+'_product_details').hide();
}

jQuery(function() {
	
	/*
	//If using checkboxes for each product
	var products = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33];
	products.forEach(function(product) {
		var name = '#wpas-meta_products-checkbox-'+product;
		if (!jQuery(name).is(':checked'))
			uncheckProduct(jQuery(name).val().replace(/\W+/g, '_').toLowerCase());
		
		jQuery(document).on('click',name,function(){
			toggleProduct(jQuery(this).val().replace(/\W+/g, '_').toLowerCase());
		});
	});
	*/
	console.log("<?=$log?>");
	//If using select input for products
	var selected = jQuery('select#meta_products option:selected').val();
	selected = selected.replace(/\W+/g, '_').toLowerCase();
	var items = ['types','details','glass'];
	items.forEach(function(item) {
		jQuery('.product_' + item + '_group').each(function(){
			if (jQuery(this).attr('id') != 'wpas-meta_' + selected + '_product_' + item)
			{
				jQuery(this).children('input[type=checkbox]').attr('checked', false);
				jQuery(this).hide();
			}
		});
	});
	
	jQuery(document).on('change','select#meta_products',function(){
		var newChoice = jQuery('select#meta_products option:selected').val();
		newChoice = newChoice.replace(/\W+/g, '_').toLowerCase();
		var items = ['types','details','glass'];
		items.forEach(function(item) {
			jQuery('.product_' + item + '_group').each(function(){
				if (jQuery(this).attr('id') != 'wpas-meta_' + newChoice + '_product_' + item)
				{
					var thisId = jQuery(this).attr('id');
					jQuery('#' + thisId + ' input[type=checkbox]').attr('checked', false);
					jQuery(this).hide();
				} else {
					jQuery(this).show();
				}
			});
		});
	});
});
</script>
<?php 
	endwhile;
endif; 
get_footer();
?>