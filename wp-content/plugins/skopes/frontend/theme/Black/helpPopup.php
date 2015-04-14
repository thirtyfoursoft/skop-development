<?php
	if( isset($_REQUEST["act"]) && $_REQUEST["act"] == 'aboutproject' ) {
		$shows = array ('10', '11', '12', '13', '14');
	} elseif ( isset($_REQUEST["act"]) && $_REQUEST["act"] == 'moreinfo' ) {
		$shows = array ('18', '10', '11', '12', '13', '14');
	} elseif ( isset($_REQUEST["act"]) && $_REQUEST["act"] == 'features' ) {
		$shows = array ('15', '16' );
	} elseif ( isset($_REQUEST["act"]) && $_REQUEST["act"] == 'getreport' ) {
		$shows = array ('17', '21' );
	} elseif ( isset($_REQUEST["act"]) && $_REQUEST["act"] == 'guide' ) {
		$shows = array ('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21' );
	} else {
		$shows = array();
	}

	if ($cuurentURL == $blogURL) {
		$shows = array ('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21' );
	}
?>
<script type="text/javascript">
$(document).ready(function() {

	var show = <?php echo json_encode($shows); ?>;
	var lastOne = show.length-1;

	if (show.length != 0) { 
		$("div#popContent_"+show[0]).show();
	}
	
	var count = 0;

	$('body').on('click', '#popNext', function(event) {
			event.preventDefault();
			count++;

			if (count <= lastOne ) {
				$("div.pop-block").hide();
				$("div#popContent_"+show[count]).show();
			} else {
				$("div.pop-block").hide();
			}

/*			var id	= $(this).parent().parent().parent().attr('id');
			var box_id_array = id.split("_");

			var box_id = box_id_array[1];
			var increment = 1;

			box_id_new =parseInt(box_id) + parseInt(increment);

			$("div#popContent_"+box_id).hide();
			$("div#popContent_"+box_id_new).show();
*/
	});

	$('body').on('click', '#closePop', function(event) {
			event.preventDefault();
			$("div.pop-block").hide();
	});
});
</script>
	<!--  ********************  Images for help side show on popup  ****************************** -->
<div class="pop-block" id="popContent_1" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
    <h2>Welcome To Skopes!</h2>
    <p>Before you get started, we have provided some introductory help and guidance.</p>
    <p>It will walk you through the admin components of the Skopes tool, and how you use it.</p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="##" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_2" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
    <h2>This is The Skopes "Home Screen"</h2>
    <p>This screen contains a step by step guide to the project set-up process, including recommended actions (behind the blue boxes).</p>
    <p>Whenever you log into Skopes you will arrive here.</p>
    <p>The blue boxs will turn green when you have completed an action, so you know how far through the process you are.</p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_3" style="display:none;">
  <div class="full-over"></div>
  <!--<div class="pop-box" style="left: 46%; width:auto;  top:10%;">-->
	<div class="pop-box">
    <div class="box-row1">
    <p><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/help3.png"></p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_4" style="display:none;">
  <div class="full-over"></div>
  <!--<div class="pop-box" style="left: 46%; width:auto;  top:10%;">-->
	<div class="pop-box">
    <div class="box-row1">
    <p><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/help4.png"></p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_5" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
    <h2>This is The Skopes "Home Screen"</h2>
    <p>We recommend that you use these blue action boxes, to navigate around Skopes during your project.</p>
    <p>However, if once you are familiar with the Skopes interfaces, you can also use the menu button at the top of each screen.</p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_6" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
    <p><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/help6.png"></p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_7" style="display:none;">
  <div class="full-over"></div>
  <!--<div class="pop-box" style="left: 28%; width:auto;  top:5%;">-->
	<div class="pop-box">
    <div class="box-row1">
    <p><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/help7.png"></p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_8" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
    <h2>Need Help?</h2>
    <p>If you are unsure what to do on any page, you can click on the help icon to access relevant information.</p>
    <p>The help text relates to the page on which it sits. It will link you off to other fuller information.</p>
    <p>Skopes provides multiple sources of help.</p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_9" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
    <p><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/help9.png"></p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_10" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
      <p>There a small number of different types of screens in Skopes.</p>
      <p>The first asks you questions about your organisation and project.</p>
      <p>The second asks you to select (or enter) the functionality that you wish to implement.</p>
      <p>Click "Next" to see examples</p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_11" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
      <p>This screen prompts you to enter information about your project.</p>
      <p>There are four sets of questions on this screen, click on the "+" or title bar to reveal the individual questions.</p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_12" style="display:none;">
  <div class="full-over"></div>
  <!--<div class="pop-box" style="left: 35%; width:auto; top:10%;">-->
	<div class="pop-box">
    <div class="box-row1">
    <p><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/help12.png"></p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_13" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
      <p>Your progress through completing the questions is displayed in the question title bar.</p>
      <p>
        The possible statuses are:<br/>
        <span class="in-progress-color">In progress</span>
        <span class="complete-color">Complete</span>
      </p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_14" style="display:none;">
  <div class="full-over"></div>
  <!--<div class="pop-box" style="left: 35%; width:auto; top:10%;">-->
	<div class="pop-box">
    <div class="box-row1">
    <p><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/help14.png"></p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_15" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
      <p>Go through every functional area and select</p>
      <p>
        "Yes" to include<br/>
        or<br/>
        "No" to exclude
      </p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_16" style="display:none;">
  <div class="full-over"></div>
  <!--<div class="pop-box" style="left: 29%; width:auto; top:8%;">-->
	<div class="pop-box">
    <div class="box-row1">
    <p><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/help16.png"></p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_17" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1 co-box">
      <p>After completing all the requirements in Stage 1, which includes:</p>
      <ul class="pop-list1">
        <li><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/bullet_1.png" alt=""> Answering all questions in My Organisation</li>
        <li><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/bullet_1.png" alt=""> Answering all questions in Project Basics</li>
        <li><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/bullet_1.png" alt=""> Incluing and excluding all functional areas</li>
      </ul>
      <p>You can now download the Project Initiatio Documents:</p>
      <ul class="pop-list1">
        <li><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/bullet_2.png" alt=""> Project Introduction and Rationale</li>
        <li><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/bullet_2.png" alt=""> Expression of Interest</li>
        <li><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/bullet_2.png" alt=""> Expression of Interest Response</li>
      </ul>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_18" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
      <h2>You Are Currently In Stage 2</h2>
      <p>This screen prompts you to enter further information about your project.</p>
      <p>There are three sets of questions on this screen, click on the "+" or title bar to reveal the individual questions.</p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_19" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
      <p>This screen prompts you to include / exclude featuers within a functional area.</p>
      <p>Click "Yes" to include and "No" to exclude.</p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_20" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1">
      <p>When you include feature, you are prompted to enter more information about the feature.</p>
      <p>Click on the blue "Save" button to save.</p>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>

<div class="pop-block" id="popContent_21" style="display:none;">
  <div class="full-over"></div>
  <div class="pop-box">
    <div class="box-row1 co-box">
      <p>After completing all the requirements in Stage 2, which includes:</p>
      <ul class="pop-list1">
        <li><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/bullet_1.png" alt=""> Answering all questions in Project Details</li>
        <li><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/bullet_1.png" alt=""> Specifying all features within required functional areas</li>
      </ul>
      <p>You can now download the Vendor Engagement and Project Management Documents:</p>
      <ul class="pop-list1">
        <li><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/bullet_2.png" alt=""> Project Carter & Scope of Work</li>
        <li><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/bullet_2.png" alt=""> Request for Quotation</li>
        <li><img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/bullet_2.png" alt=""> Request for Quotation Respose</li>
      </ul>
    </div>
    <div class="box-row2">
      <a href="#" class="get-started-but" id="closePop"><strong>Dismiss</strong> help and get started</a>
      <a href="#" class="help-screen-but" id="popNext"><strong>Next</strong> help screen <img src="<?php echo RC_TC_PLUGIN_URL ?>frontend/theme/<?=$choosenTheme; ?>/images/help/right-arrow1.png" alt=""></a>
    </div>
  </div>
</div>
	<!--  ********************  End Images for help side show on popup  ****************************** -->
