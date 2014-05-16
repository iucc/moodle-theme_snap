<?php
/**
 * Flexpage local library
 * @see format_flexpage_default_width_styles
 */
require_once($CFG->dirroot.'/course/format/flexpage/locallib.php');

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidetop = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-top', $OUTPUT));
$hassidepre = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-pre', $OUTPUT));
$hassidepost = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-post', $OUTPUT));
$haslogininfo = (empty($PAGE->layout_options['nologininfo']));

$showsidepre = ($hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT));
$showsidepost = ($hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT));

// Always show block regions when editing so blocks can
// be dragged into empty block regions.
if ($PAGE->user_is_editing()) {
    if ($PAGE->blocks->is_known_region('side-pre')) {
        $showsidepre = true;
        $hassidepre  = true;
    }
    if ($PAGE->blocks->is_known_region('side-post')) {
        $showsidepost = true;
        $hassidepost  = true;
    }
    if ($PAGE->blocks->is_known_region('side-top')) {
        $hassidetop = true;
    }
}

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$courseheader = $coursecontentheader = $coursecontentfooter = $coursefooter = '';
if (empty($PAGE->layout_options['nocourseheaderfooter'])) {
    $courseheader = $OUTPUT->course_header();
    $coursecontentheader = $OUTPUT->course_content_header();
    if (empty($PAGE->layout_options['nocoursefooter'])) {
        $coursecontentfooter = $OUTPUT->course_content_footer();
        $coursefooter = $OUTPUT->course_footer();
    }
}

$bodyclasses = array();
if ($showsidepre && !$showsidepost) {
    if (!right_to_left()) {
        $bodyclasses[] = 'side-pre-only';
    } else {
        $bodyclasses[] = 'side-post-only';
    }
} else if ($showsidepost && !$showsidepre) {
    if (!right_to_left()) {
        $bodyclasses[] = 'side-post-only';
    } else {
        $bodyclasses[] = 'side-pre-only';
    }
} else if (!$showsidepost && !$showsidepre) {
    $bodyclasses[] = 'content-only';
}
if ($hascustommenu) {
    $bodyclasses[] = 'has_custom_menu';
}

echo $OUTPUT->doctype() ?>

<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
<title><?php echo $OUTPUT->page_title(); ?></title>
<link rel="shortcut icon" href="<?php echo $OUTPUT->favicon() ?>"/>
<?php echo $OUTPUT->standard_head_html() ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='http://fonts.googleapis.com/css?family=Roboto:500,100,400,300' rel='stylesheet' type='text/css'>

    <?php echo format_flexpage_default_width_styles() ?>

    <style>
   #region-top {
   	width:100%;
   	clear:both;
    }
    #page-content #region-pre,
    #page-content #region-post,
    #page-content #region-main-box
    {
    	float:left;
    	width:25%;
    	left:0;
    }

    #page-content #region-main-box {
    	width:46%;
    	margin:0 2%;
    }


	.side-pre-only #page-content #region-main-box,
	.side-post-only #page-content #region-main-box{
    	width:70%;
    	margin:0 auto;
    }

    .block_settings {
		width: auto;
		height: auto;
		visibility: visible;
		position: relative;
		background-color: #FFF !important;
		right:0;
	}

   .flexpage_actionbar {
       margin: 5px 10px;
   }

   .flexpage_prev_next #format_flexpage_next_page {
       display: block;
       float: right;
   }

   /* Makes the target larger for when you are dragging blocks into an empty top region */
   .format-flexpage #region-top .block-region {
       min-height: 20px;
   }

    .course-content {
        max-width: none;
    }

    .format_flexpage_tabs  #custommenu {
         padding-left: 10px;
         padding-right: 10px;
    }
    </style>
</head>

<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<?php include(__DIR__.'/nav.php'); ?>


<!-- moodle js hooks -->
<div id="page">
<div id="page-content">


<!--
////////////////////////// MAIN  ///////////////////////////////
-->
<main id="moodle-page" class="clearfix">

<header id="page-header" class="clearfix">
<nav class="breadcrumb-nav" role="navigation" aria-label="breadcrumb"><?php echo $OUTPUT->navbar(); ?></nav>
<div id="page-mast">
<?php
echo $OUTPUT->page_heading();
echo $OUTPUT->course_header();
?>
</div>

</header>

<!-- not sure what this does in flexpage if anything -->
<?php echo $OUTPUT->print_settings_link(); ?>
<!-- flexpage tab bar -->
<?php echo format_flexpage_tabs(); ?>

<!-- Flexpage editing menu/custommenu -->
<div id="flexpage_actionbar" class="flexpage_actionbar clearfix">
    <?php echo $OUTPUT->main_content(); ?>
</div>


<!-- top box -->
<?php if ($hassidetop) { ?>
<div id="region-top" class="block-region">
    <?php echo $OUTPUT->blocks('side-top'); ?>
</div>
<?php } ?>


<!-- next / previous buttons -->
<?php if (format_flexpage_has_next_or_previous()) { ?>
<div class="flexpage_prev_next">
<?php
echo format_flexpage_previous_button();
echo format_flexpage_next_button();
?>
</div>
<?php } ?>

<!-- blocks pre -->
<?php if ($hassidepre) { ?>
<div id="region-pre" class="block-region">
<?php echo $OUTPUT->blocks('side-pre'); ?>
</div>
<?php } ?>


<!-- actual main content -->
<div id="region-main-box">
<?php echo $OUTPUT->blocks('main'); ?>
</div>


<!-- blocks post -->
<?php if ($hassidepost) { ?>
<div id="region-post" class="block-region">
<?php echo $OUTPUT->blocks('side-post'); ?>
</div>
<?php } ?>


</main>

</div>
</div>
<!-- close moodle js hooks -->



<?php include(__DIR__.'/footer.php'); ?>