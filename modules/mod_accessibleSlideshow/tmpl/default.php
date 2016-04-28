<?php
/**
 * @package   Accessible Slideshow - accessibletemplate
 * @version   1.0.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_JEXEC') or die( 'Restricted access' );
	
/*==========================================================================
   VARIABLES & PARAMETERS DEFINITION
==========================================================================*/
$doc = JFactory::getDocument();
define("SLIDESHOW_LOCAL_DIR",dirname(__FILE__)."/../");
require_once(SLIDESHOW_LOCAL_DIR.'modules/parameters-handler.php');
$accessSlideshowId=rand(); // This random number is used to identify a slideshow in case more than one slideshow is printed in the same page
?>

<!--ACCESSIBLE SLIDESHOW-->
<?php
/*==========================================================================
   ACCESSIBLE SLIDESHOW
==========================================================================*/
echo '<div id="accessible-slideshow-ID_'.$accessSlideshowId.'" ';
echo 'class="accessible-slideshow_outer ';
echo 'text-over-images_'.ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_TEXT_OVER_IMAGES;
echo '">';
?>

	<?php //Print the slideshow hidden heading
	if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_HIDDEN_HEADING_ENABLED=='true'){
		echo '<'.ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_HIDDEN_HEADING_LEVEL.' class="visually-hidden">';
		echo ZHONGFRAMEWORK_LANGUAGE_ACCESSIBLE_SLIDESHOW_MAIN_HEADING;
		echo '</'.ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_HIDDEN_HEADING_LEVEL.'>';
		}

	?>

	<?php
	/*----------------------------------------------------------------
	-  NAVIGATION DOTS
	---------------------------------------------------------------- */
	//Print the navigation dots if enabled (only in default-layout) and ALWAYS print them in mobile (even if they are disabled, for a matter of usability)
	if((ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ENABLE_DOTS=="true" && ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout") || 
	   ZHONGFRAMEWORK_LAYOUT_MODE=="mobile-layout"): ?>
	<!--Slideshow navigation dots-->
	<div class="accessible-slideshow_dots-container">
		<ol class="accessible-slideshow_dots-container-inner">
			<?php 
				for($i=0;$i<20;$i++){
					if($accessSlideshow_slidesImagesPath[$i]!="" && $accessSlideshow_slidesTitles[$i]!=""){
						echo '<li class="accessible-slideshow_dot-wrapper">';
						echo '<button class="accessible-slideshow_dot accessible-slideshow_dot-'.$i.'">';
						echo '<span class="accessible-slideshow_dot-decoration-outer"><span class="accessible-slideshow_dot-decoration-inner">';
						echo $accessSlideshow_slidesTitles[$i];						
						echo '</span></span>';
						echo '<span class="visually-hidden"> ('.ZHONGFRAMEWORK_LANGUAGE_ACCESSIBLE_SLIDESHOW_SLIDESHOW_BUTTON.')</span>';
						echo '</button></li>';
						}
					}
				?>
		</ol>
	</div>
	<?php endif; ?>
	
	<div class="accessible-slideshow" aria-live="off">
		<?php
			for($i=0,$slidesNumber=0;$i<20;$i++){
				//If an image AND a title is set, print the slide!
				if($accessSlideshow_slidesImagesPath[$i]!="" && $accessSlideshow_slidesTitles[$i]!=""){
					//Print the slide container
					echo '<div class="slide slide-'.$i.'" id="slideshowID-'.$accessSlideshowId.'-slideN-'.$i.'">';
					//Print the slide text container
					echo '<div class="slide-text ';
					if($accessSlideshow_slidesTextVisible[$i]=='false')
						echo 'visually-hidden ';
					echo '">';
					//Print the slide title
					echo '<'.ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_SLIDES_HEADING_LEVEL.' class="slide-heading">';
					if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_LINKED_TITLES=="true" && $accessSlideshow_slidesLinks[$i]!="") // if linked title, print <a>
						echo '<a href="'.$accessSlideshow_slidesLinks[$i].'">';
					echo $accessSlideshow_slidesTitles[$i];
					if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_LINKED_TITLES=="true")
						echo '</a>';
					echo '</'.ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_SLIDES_HEADING_LEVEL.'>';
					//If a description is set, print it!
					if($accessSlideshow_slidesDescriptions[$i]!="")
						echo '<p>'.$accessSlideshow_slidesDescriptions[$i].'</p>';
					echo '</div>'; // end text DIV container
					// And now, print the image:
					echo '<p class="wrapper-element slide-image-container">';
					if($accessSlideshow_slidesLinks[$i]!="") // if a link is set, then make the image a link
						echo '<a href="'.$accessSlideshow_slidesLinks[$i].'">';
					echo '<img src="'.$accessSlideshow_slidesImagesPath[$i].'"';
					echo ' alt="'.$accessSlideshow_slidesImagesAlt[$i].'" />'; // END generating the image
					if($accessSlideshow_slidesLinks[$i]!="")
						echo '</a>';
					echo '</p>';
					echo '</div><hr class="removed"/>'; // end slide DIV container
					$slidesNumber++; // Count the number of slides
					} //END if
				} //END for
			?>
	</div>
	
	<?php
	/*----------------------------------------------------------------
	-  NAVIGATION ARROWS
	---------------------------------------------------------------- */
	if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ENABLE_ARROWS=="true" && ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"): ?>
	<!--Slideshow navigation arrows-->
	<div class="accessible-slideshow_arrows-container">
		<button class="accessible-slideshow_arrow accessible-slideshow_arrow-left">
			<span class="accessible-slideshow_arrow-decoration-outer">
				<span class="accessible-slideshow_arrow-decoration-inner"></span>
				<span class="visually-hidden"> (<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESSIBLE_SLIDESHOW_PREVIOUS_SLIDE_BUTTON;?>)</span>
			</span>
		</button>
		<button class="accessible-slideshow_arrow accessible-slideshow_arrow-right">
			<span class="accessible-slideshow_arrow-decoration-outer">
				<span class="accessible-slideshow_arrow-decoration-inner"></span>
				<span class="visually-hidden"> (<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESSIBLE_SLIDESHOW_NEXT_SLIDE_BUTTON;?>)</span>
			</span>
		</button>
	</div>
	<?php endif; ?>
	
</div>

<?php
/*==========================================================================
   CSS CUSTOM STYLE
==========================================================================*/
// Start catching the output (it will be then imported into the head of the document)
ob_start();

require_once(SLIDESHOW_LOCAL_DIR.'css/accessibleslideshow-style.css');
?>
<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_SLIDESHOW_WIDTH!=""){ ?>
	.default-layout .accessible-slideshow_outer{width:<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_SLIDESHOW_WIDTH; ?>;}
	<?php } ?>
.default-layout .accessible-slideshow_outer,
.default-layout .accessible-slideshow,
.default-layout .accessible-slideshow .slide{height:<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_SLIDESHOW_HEIGHT; ?>;}
<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_DOTS_COLOR_2!=""){ ?>
	.accessible-slideshow_dot .accessible-slideshow_dot-decoration-inner,
	.accessible-slideshow_dot.activeDot .accessible-slideshow_dot-decoration-outer,
	.accessible-slideshow_dot.focusedAnchor .accessible-slideshow_dot-decoration-outer{background-color:#<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_DOTS_COLOR_2; ?>;}
	<?php } ?>
<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_DOTS_COLOR_1!=""){ ?>
	.accessible-slideshow_dot .accessible-slideshow_dot-decoration-outer,
	.accessible-slideshow_dot.activeDot .accessible-slideshow_dot-decoration-inner,
	.accessible-slideshow_dot.focusedAnchor .accessible-slideshow_dot-decoration-inner{background-color:#<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_DOTS_COLOR_1; ?>;}
	<?php } ?>
<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ARROWS_COLOR_1!=""){ ?>
	.accessible-slideshow_arrow-left .accessible-slideshow_arrow-decoration-outer{border-right-color:#<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ARROWS_COLOR_1; ?>;}
	.accessible-slideshow_arrow-right .accessible-slideshow_arrow-decoration-outer{border-left-color:#<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ARROWS_COLOR_1; ?>;}
	<?php } ?>
<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ARROWS_COLOR_2!=""){ ?>
	.accessible-slideshow_arrow-left .accessible-slideshow_arrow-decoration-inner{border-right-color:#<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ARROWS_COLOR_2; ?>;}
	.accessible-slideshow_arrow-right .accessible-slideshow_arrow-decoration-inner{border-left-color:#<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ARROWS_COLOR_2; ?>;}
	<?php } ?>
<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_TEXT_BACKGROUND_COLOR!=""){ ?>
	.default-layout .accessible-slideshow .slide-text,
	.mobile-layout .accessible-slideshow .slide-text{background-color:#<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_TEXT_BACKGROUND_COLOR; ?>;}
	<?php } ?>
<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_TITLES_COLOR!=""){ ?>
	.default-layout .accessible-slideshow .slide-heading,
	.mobile-layout .accessible-slideshow .slide-heading,
	.default-layout .accessible-slideshow .slide-heading a,
	.mobile-layout .accessible-slideshow .slide-heading a{color:#<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_TITLES_COLOR; ?>;}
	<?php } ?>
<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_TITLES_FONT_SIZE!=""){ ?>
	.default-layout .accessible-slideshow .slide-text .slide-heading,
	.mobile-layout .accessible-slideshow .slide-text .slide-heading{font-size:<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_TITLES_FONT_SIZE; ?>;}
	<?php } ?>
<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_DESCRIPTION_COLOR!=""){ ?>
	.default-layout .accessible-slideshow .slide-text p,
	.mobile-layout .accessible-slideshow .slide-text p{color:#<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_DESCRIPTION_COLOR; ?>;}
	<?php } ?>
<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_DESCRIPTION_FONT_SIZE!=""){ ?>
	.default-layout .accessible-slideshow .slide-text p,
	.mobile-layout .accessible-slideshow .slide-text p{font-size:<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_DESCRIPTION_FONT_SIZE; ?>;}
	<?php } ?>
<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_FULLY_RESPONSIVE_IMAGES=="true"){ ?>
	.default-layout .accessible-slideshow img,
	.mobile-layout .accessible-slideshow img{min-width:100%;}
	<?php } ?>
.default-layout .accessible-slideshow .slide-text{height:<?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_TEXT_CONTAINER_HEIGHT; ?>}

<?php
// Flush the output into a variable
$outputBuffer = ob_get_contents();
ob_end_clean();

// Import the style into head (using the Joomla api)
$doc->addStyleDeclaration( $outputBuffer );
?>

<?php
/*==========================================================================
   JAVASCRIPT HANDLER
==========================================================================*/
//Enable slideshow animation only in default layout and mobile layout
if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" || ZHONGFRAMEWORK_LAYOUT_MODE=="mobile-layout"):
?>
<script type="text/javascript">
(function(){
	/*----------------------------------------------------------------
	-  Slideshow initialization
	---------------------------------------------------------------- */
	var manualSlideChanged = false;		
	var animationInProgress = false; //Prevent multiple animations at the same time
	var activeSlideIndex = 0;
	var nextSlideIndex;
	var $activeSlide;
	var $nextSlide;
	var heightTemp; //Dynamically calculate the height of the slideshow
	var slidesNumber = <?php echo $slidesNumber; ?>;
	var animationSpeed = <?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ANIMATION_SPEED; ?>;
	var animationInterval = <?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ANIMATION_INTERVAL; ?>;
	var enableDots =  <?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ENABLE_DOTS; ?>;
	var enableArrows =  <?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ENABLE_ARROWS; ?>;
	var enableLinkedTitles =  <?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_LINKED_TITLES; ?>;
	var textOverImages = <?php echo ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_TEXT_OVER_IMAGES; ?>;
	var $accessibleSlideshow = jQuery('#accessible-slideshow-ID_<?php echo $accessSlideshowId; ?>');
	
	//Slides initialization:
	jQuery('.accessible-slideshow .slide').css({'opacity':'0','display':'none'}).addClass('hidden-slide');
	jQuery('.accessible-slideshow .slide:first-child').css({'opacity':'1','display':'block'}).removeClass('hidden-slide').addClass('active-slide');
	
	//Style initialization
	jQuery('.default-layout .accessible-slideshow_arrow').css('display','block').css('opacity','0');
	jQuery('.accessible-slideshow_dot-wrapper:first-child .accessible-slideshow_dot').addClass('activeDot');
	jQuery('.default-layout .text-over-images_true .slide-text').css('position','absolute').css('bottom','0').css('left','0');
	
	//Initialize dots style
	<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ENABLE_DOTS=="true"): ?>
		jQuery(document).ready(function() {
			//By default, hide the dots
				jQuery('.default-layout .accessible-slideshow_dots-container').css('opacity','0');
				//If the device is a tablet or mobile, then show the dots by default
				if(zhongFramework.isPortable){
					jQuery('.default-layout .accessible-slideshow_dots-container').css('opacity','0.4');
					}
		});
		<?php endif; ?>
	
	//Initialize arrow style	
	<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ENABLE_ARROWS=="true" && ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"): ?>
		jQuery('.accessible-slideshow_arrow-left .accessible-slideshow_arrow-decoration-inner').html(jQuery('.accessible-slideshow .slide').eq(-1).find('.slide-heading').html());
		jQuery('.accessible-slideshow_arrow-right .accessible-slideshow_arrow-decoration-inner').html(jQuery('.accessible-slideshow .slide').eq(1).find('.slide-heading').html());
		<?php endif; ?>
		
	/*----------------------------------------------------------------
	-  Slideshow handler (slide change)
	---------------------------------------------------------------- */
	//"requestedSlideIndex" values: -1 (show next slide) ; -2 (show previous slide) ; >0 (requested slide index)
	var nextSlideAnimation = function(requestedSlideIndex){

		// If animation is still in progress -or- the requested slide is the same as the one showed, then do nothing.
		if(animationInProgress || requestedSlideIndex===activeSlideIndex){return;}
		
		//Else, start the animation		
		animationInProgress=true;
		
		/**
		 * Get the next active slide index
		**/
		if(requestedSlideIndex==-2){ // "-2" = show previous slide
			nextSlideIndex = activeSlideIndex-1;
			if(nextSlideIndex==-1){nextSlideIndex=slidesNumber-1;} // if beginning is reached, go to the last slide
			}
		else{
			if(requestedSlideIndex==-1){ // "-1" = show next slide
				nextSlideIndex = activeSlideIndex+1;
				if(nextSlideIndex==slidesNumber){nextSlideIndex=0;} // if end is reached, go to the first slide
				}
			else{ // this case is active when a dot is clicked
				nextSlideIndex = requestedSlideIndex;
				}
			}
		
		/**
		 * Get the current/next slides reverences
		**/
		$nextSlide = $accessibleSlideshow.find('.slide').eq(nextSlideIndex);
		$activeSlide = $accessibleSlideshow.find('.slide').eq(activeSlideIndex);
		
		/**
		 * Update the "live-aria" attributes (only if the slideshow was changed manually)
		**/
		if(manualSlideChanged){
			$accessibleSlideshow.find('.accessible-slideshow').removeAttr('aria-live');
			$activeSlide.removeAttr('aria-live');
			$nextSlide.attr('aria-live','rude');
			}
		
		/**
		 * Toggle active/hidden slides
		**/
		$activeSlide
			.removeClass('active-slide').addClass('hidden-slide');
		$nextSlide
			.removeClass('hidden-slide').addClass('active-slide').css('display','block');
		
		/**
		 * Animate the process:
		**/
		//Active slide fade-in
		$activeSlide
			.animate({'opacity' : '0'}, {queue:true, duration:animationSpeed })
			.queue(function(){
				//At animation finished, hide the slide
				jQuery(this).css('display','none');
				jQuery(this).dequeue();
				});
		//Previous slide fade-out
		$nextSlide
			.animate({'opacity' : '1'}, {queue:true, duration:animationSpeed})
			.queue(function(){				
				//When the new slide is showed, "unbind" the animation
				animationInProgress=false;
				jQuery(this).dequeue();
				});
		
		/**
		 * Update Dots
		**/
		jQuery('.accessible-slideshow_dot.activeDot').removeClass('activeDot');
		jQuery('.accessible-slideshow_dot').eq(nextSlideIndex).addClass('activeDot');
		
		<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ENABLE_ARROWS=="true" && ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"): ?>
			/**
			 * Update title and hash of the arrows:
			**/
			//Get the index of the previous/next slides (except cases happen when begin/end reached)
			var previousActiveSlideIndex = nextSlideIndex-1;
			var nextActiveSlideIndex = nextSlideIndex+1;
			if(previousActiveSlideIndex==-1){previousActiveSlideIndex=slidesNumber-1;}
			if(nextActiveSlideIndex==slidesNumber){nextActiveSlideIndex=0;}
			//Update the text of the arrows (equal to the title of the slide)
			jQuery('.accessible-slideshow_arrow-left .accessible-slideshow_arrow-decoration-inner').html(jQuery('.accessible-slideshow .slide').eq(previousActiveSlideIndex).find('.slide-heading').html());
			jQuery('.accessible-slideshow_arrow-right .accessible-slideshow_arrow-decoration-inner').html(jQuery('.accessible-slideshow .slide').eq(nextActiveSlideIndex).find('.slide-heading').html());
			<?php endif; ?>
		
		activeSlideIndex = nextSlideIndex;
		
		}
	
	/*----------------------------------------------------------------
	-  Repeat the animation every N sec. (ONLY in default layout)
	---------------------------------------------------------------- */
	var accessSlideshowInterval;
	if(jQuery("body").hasClass('default-layout')){
		accessSlideshowInterval = setInterval(function(){ nextSlideAnimation(-1); },animationInterval);
		}
	
	/*----------------------------------------------------------------
	-  Slideshow stop/restart handler (ONLY in default layout)
	---------------------------------------------------------------- */	
	jQuery('.default-layout .accessible-slideshow_outer').hover( // When the slideshow is hovered with the pointer, stop it
		function(){
			if(!manualSlideChanged) { clearInterval(accessSlideshowInterval); }
			},
		function(){
			// if any of the "navigation arrow" hasn't been clicked, restart the animation
			if(!manualSlideChanged) { accessSlideshowInterval = setInterval(function(){ nextSlideAnimation(-1); },animationInterval); }
			}
		);
	
	/*----------------------------------------------------------------
	-  Slideshow dots/arrows animations (hover)
	---------------------------------------------------------------- */
	// Dots container hovered
	jQuery('.default-layout .accessible-slideshow_outer').hover( 
		function(){
			jQuery('.default-layout .accessible-slideshow_arrow').animate({'opacity' : '0.4'}, { queue:false, duration:300});
			jQuery('.default-layout .accessible-slideshow_dots-container').animate({'opacity' : '0.4'}, { queue:false, duration:300});
			},
		function(){
			jQuery('.default-layout .accessible-slideshow_arrow').animate({'opacity' : '0'}, { queue:false, duration:300});
			jQuery('.default-layout .accessible-slideshow_dots-container').animate({'opacity' : '0'}, { queue:false, duration:300});
			}
		);
	// Opacity effect for the hovered arrows
	jQuery('.default-layout .accessible-slideshow_arrow').hover(
		function()
			{jQuery(this).animate({'opacity' : '0.8'}, { queue:false, duration:300});},
		function()
			{jQuery(this).animate({'opacity' : '0.4'}, { queue:false, duration:300});}
		);
	// Opacity effect for the hovered dots
	jQuery('.default-layout .accessible-slideshow_dots-container').hover(
		function()
			{jQuery(this).animate({'opacity' : '0.8'}, { queue:false, duration:300});	},
		function()
			{jQuery(this).animate({'opacity' : '0.4'}, { queue:false, duration:300});	}
		);
	
	/*----------------------------------------------------------------
	-  Arrows click handler
	---------------------------------------------------------------- */
	jQuery('.accessible-slideshow_arrow').click(function(e){
		
		if(animationInProgress){return;}

		//this stops the animation circle
		manualSlideChanged=true;
		
		if(jQuery(this).hasClass('accessible-slideshow_arrow-right')){
			nextSlideAnimation(-1); // -2 = show next slide
			}
		if(jQuery(this).hasClass('accessible-slideshow_arrow-left')){
			nextSlideAnimation(-2); // -2 = show previous slide
			}
		});
	
	/*----------------------------------------------------------------
	-  Dots click handler
	---------------------------------------------------------------- */
	jQuery('.accessible-slideshow_dot').click(function(e){

		if(animationInProgress){return false;}

		//this stops the animation circle
		manualSlideChanged=true;

		nextSlideAnimation(jQuery(this).parent().index());
		});
		
	/*----------------------------------------------------------------
	-  Focus handler for dot navigation
	---------------------------------------------------------------- */
	jQuery(".accessible-slideshow_dot").bind("focus",function(){

		// When focused, stop animation
		clearInterval(accessSlideshowInterval);
		
		//Show the dots
		jQuery('.accessible-slideshow_dots-container').css('opacity','0.9');
		
		//Add the class for the "active style", plus, a special class to specify the keyboard focus
		jQuery(this).addClass('focusedAnchor');
		
		});
	jQuery(".accessible-slideshow_dot").bind("focusout",function(){
		
		//Remove the "active style" and also the special class for the keyboard focus
		jQuery(this).removeClass('focusedAnchor');
		
		});
	
	/*----------------------------------------------------------------
	-  Focus handler for arrow navigation
	---------------------------------------------------------------- */
	<?php if(ZHONGFRAMEWORK_ACCESSIBLE_SLIDESHOW_ENABLE_ARROWS=="true" && ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"): ?>
	
		jQuery('.accessible-slideshow_arrow')
			.bind("focus",function(){
				//Show arrow
				jQuery(this).css({'display' : 'block','opacity' : '0.9'});
				//Add a special class to specify the keyboard focus
				jQuery(this).addClass('focusedAnchor');
				})
			.bind("focusout",function(){
				//Hide arrow
				jQuery(this).css({'display' : 'block','opacity' : '0.4'});
				//Remove the special class
				jQuery(this).removeClass('focusedAnchor');				
				});

		<?php endif; ?>
}()); //END accessible slideshow
</script>
<?php endif;// END if(default-layout or mobile-layout) ?>
