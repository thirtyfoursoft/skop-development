<!---Footer--->
<footer id="footer">
	<div class="container">
		<div class="four-colum">
			<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Links')) : ?>
				<div class="one-four">
					<!--<h5>Augue Ultricies</h5>
					<ul>
						<li><a href="#">Donec sed diam blandit </a></li>
						<li><a href="#">commodo tortor in </a></li>
						<li><a href="#">condimentum quam </a></li>
						<li><a href="#">Mauris lobortis fermentum </a></li>
					</ul>-->
				</div>
				<div class="one-four">
					<!--<h5>Malesuada Purus</h5>
					<ul>
						<li><a href="#">Aliquam hendrerit rhoncus </a></li>
						<li><a href="#">lorem in porta</a></li>
						<li><a href="#">Curabitur ullamcorper</a></li>
						<li><a href="#">Metus quis mattis tempus, felis </a></li>
					</ul>-->
				</div>
				<div class="one-four">
					<!--<h5>Proin sagittis</h5>
					<ul>
						<li><a href="#">Sapien, ut iaculis risus </a></li>
						<li><a href="#">Augue eu sem</a></li>
						<li><a href="#">Phasellus eget laoreet dui</a></li>
						<li><a href="#">Eget placerat ligula</a></li>
					</ul>-->
				</div>
			<?php endif; ?>
			<div class="one-four last-colum">
				<!--<span>Web strategy and design by</span>
				<a href="#"><img alt="" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/footer_logo.png"></a>-->
				<img alt="" src="<?php echo RC_TC_PLUGIN_URL ?>frontend/images/footer-brankdmark.png">
			</div>
		</div>
	</div>
</footer>
<!---End Footer--->
