<div class="row">
	<div class="col-md-offset-3 col-md-6" id="main-content">
		<div class="page-header">
			<h2><?php echo __('FogBugz Login'); ?></h2>
		</div>
		<?php echo $this->Form->create('User', array('cherry' => 'form-horizontal')); ?>
			<?php echo $this->Form->input('fogbugz_url', array('label' => 'FogBugz URL')); ?>
			<?php echo $this->Form->input('email'); ?>
			<?php echo $this->Form->input('password'); ?>
			<div class="form-group submit">
				<div class="col-md-offset-3 col-md-9">
					<?php echo $this->Form->submit(__('Login'), array('div' => false, 'class' => 'btn btn-success')); ?>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
