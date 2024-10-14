<article class="post post-full card bg-white shadow-sm border-0" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="post-header text-center<?php if (argon_has_post_thumbnail() && get_option('argon_show_thumbnail_in_banner_in_content_page') != 'true'){echo " post-header-with-thumbnail";}?>">
		<?php
			if (argon_has_post_thumbnail() && get_option('argon_show_thumbnail_in_banner_in_content_page') != 'true'){
				$thumbnail_url = argon_get_post_thumbnail();
				echo "<img class='post-thumbnail' src='" . $thumbnail_url . "'></img>";
				echo "<div class='post-header-text-container'>";
			}
			if (argon_has_post_thumbnail() && get_option('argon_show_thumbnail_in_banner_in_content_page') == 'true'){
				$thumbnail_url = argon_get_post_thumbnail();
				echo "
				<style>
					body section.banner {
						background-image: url(" . $thumbnail_url . ") !important;
					}
				</style>";
			}
		?>
		<a class="post-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		<div class="post-meta">
			<?php
				$metaList = explode('|', get_option('argon_article_meta', 'time|views|comments|categories'));
				if (is_sticky() && is_home() && ! is_paged()){
					array_unshift($metaList, "sticky");
				}
				if (post_password_required()){
					array_unshift($metaList, "needpassword");
				}
				if (is_meta_simple()){
					array_remove($metaList, "time");
					array_remove($metaList, "edittime");
					array_remove($metaList, "categories");
					array_remove($metaList, "author");
				}
				if (count(get_the_category()) == 0){
					array_remove($metaList, "categories");
				}
				for ($i = 0; $i < count($metaList); $i++){
					if ($i > 0){
						echo ' <div class="post-meta-devide">|</div> ';
					}
					echo get_article_meta($metaList[$i]);
				}
			?>
			<?php if (!post_password_required() && get_option("argon_show_readingtime") != "false" && is_readingtime_meta_hidden() == False) {
				echo get_article_reading_time_meta(get_the_content());
			} ?>
		</div>
		<?php
			if (has_post_thumbnail() && get_option('argon_show_thumbnail_in_banner_in_content_page') != 'true'){
				echo "</div>";
			}
		?>
	</header>

	<div class="post-content" id="post_content">
		
		<?php
			global $post_references, $post_reference_keys_first_index, $post_reference_contents_first_index;
			$post_references = array();
			$post_reference_keys_first_index = array();
			$post_reference_contents_first_index = array();
			
			the_content();
				
		?>
		
	</div>

<!-- 在下方自定义内容-->
	
    <!--表单开始-->

	<div id="frdlink-form-modal" class="modal" style="display:none;">
		<div class="frdlink-modal-content">
		<!--div class="form-group"-->
			<span class="close-apply-link">&times;</span>
			<h2>申请友情链接</h2>
			
			<form id="friend-link-form" method="post" class="mt20" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" style="margin-bottom:20px; ">
			
				<label for="friend-web-name"><font color="#68a0ca">*</font> 网站名称:</label>
				<input type="text" size="40" value="" class="form-control" id="friend-web-name" placeholder="例如：并非正文" name="friend-web-name" />

				<label for="friend-web-url"><font color="#68a0ca">*</font> 网站链接:</label>
				<input type="text" size="40" value="" class="form-control" id="friend-web-url" placeholder="请输入带http://或https://的链接！" name="friend-web-url" />

				<label for="friend-web-image"><font color="#68a0ca">*</font> 站点Logo:</label>
				<input type="text" size="40" value="" class="form-control" id="friend-web-image" placeholder="请输入站点Logo地址" name="friend-web-image" />

				<label for="friend-web-motto">  描述:</label>
				<input type="text" size="40" value="" class="form-control" id="friend-web-motto" placeholder="请输入简介" name="friend-web-motto" />

				<label for="friend-web-rss">  RSS地址:</label>
				<input type="text" size="40" value="" class="form-control" id="friend-web-rss" placeholder="请输入RSS地址" name="friend-web-rss" />
			
			
				<div>
					<input type="hidden" value="send" name="friend-web-form" />
					<button type="submit" class="btn btn-primary">提交申请</button>
					<button type="reset" class="btn btn-default">重填</button>
					<span style="color: #68a0ca;">*</span>（提示：带有浅蓝色星号<span style="color: #68a0ca; ">*</span>的项，表示必填项~）
				</div>
			</form>
		</div>
	</div>
	<!--表单结束-->

	<style>
		.modal {
			display: none; 
			position: fixed; 
			z-index: 1; 
			left: 0;
			top: 0;
			width: 100%; 
			height: 100%; 
			overflow: auto; 
			background-color: rgb(0,0,0); 
			background-color: rgba(0,0,0,0.4); 
			padding-top: 60px;
		}
		
		.frdlink-modal-content {
			background-color: #fefefe;
			margin: 5% auto; 
			padding: 20px;
			border: 1px solid #888;
			width: 80%; 
		}
		
		.close-apply-link {
			color: #aaa;
			float: right;
			font-size: 28px;
			font-weight: bold;
		}
		
		.close-apply-link:hover,
		.close-apply-link:focus {
			color: black;
			text-decoration: none;
			cursor: pointer;
		}
	</style>

<!-- 在上方自定义内容-->
        
	<?php
		$referenceList = get_reference_list();
		if ($referenceList != ""){
			echo $referenceList;
		}
	?>

	<?php if (has_tag()) { ?>
		<div class="post-tags">
			<i class="fa fa-tags" aria-hidden="true"></i>
			<?php
				$tags = get_the_tags();
				foreach ($tags as $tag) {
					echo "<a href='" . get_category_link($tag -> term_id) . "' target='_blank' class='tag badge badge-secondary post-meta-detail-tag'>" . $tag -> name . "</a>";
				}
			?>
		</div>
	<?php } ?>
</article>