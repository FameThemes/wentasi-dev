<?php $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author')); ?>

<div class="ft-bauthor clearfix">

	<div class="ft-atava span2">

		<?php echo get_avatar( $curauth->user_email, '100' ); ?>

	</div>

	<div class="ft-atinfo span10 right">

		<div class="vcard author ft-attitle"><h4><span class="fn"><a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->display_name; ?></a></span></h4></div>

		<div class="ft-atdescr"><p><?php echo $curauth->description; ?></p></div>

	</div>

</div>