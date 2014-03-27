<div class="row">
	<div class="col-md-3 sidebar">
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo __('Actions'); ?></div>
			<div class="list-group">
				<?php echo $this->Html->link(__('List Users'), array('action' => 'index'), array('class' => 'list-group-item')); ?>
				<?php echo $this->Html->link(__('Add User'), array('action' => 'add'), array('class' => 'list-group-item active')); ?>
			</div>
		</div>
	</div>
	<div class="col-md-9 content">
		<h2><?php echo __('Add User'); ?></h2>
		<?php echo $this->Form->create('User', array('cherry' => 'form-horizontal')); ?>
			<fieldset>
				<legend><?php echo __('User Information'); ?></legend>
				<?php echo $this->Form->input('user_id', array('label' => 'FogBugz User')); ?>
			</fieldset>
		<?php echo $this->Form->end(__('Submit')); ?>
	</div>
</div>
