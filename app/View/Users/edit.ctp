<div class="row">
	<div class="col-md-3 sidebar">
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo __('Actions'); ?></div>
			<div class="list-group">
				<?php echo $this->Html->link(__('List Users'), array('action' => 'index'), array('class' => 'list-group-item')); ?>
				<?php echo $this->Html->link(__('Add User'), array('action' => 'add'), array('class' => 'list-group-item')); ?>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo __('User Actions'); ?></div>
			<div class="list-group">
				<?php echo $this->Html->link(__('View User'), array('action' => 'view', $this->Form->value('User.id')), array('class' => 'list-group-item')); ?>
				<?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $this->Form->value('User.id')), array('class' => 'list-group-item active')); ?>
				<?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $this->Form->value('User.id')), array('class' => 'list-group-item', 'confirm' => __('Are you sure you want to delete this record (ID: %s)?', $this->Form->value('User.id')))); ?>
			</div>
		</div>
	</div>
	<div class="col-md-9 content">
		<h2><?php echo __('Edit User'); ?></h2>
		<?php echo $this->Form->create('User', array('cherry' => 'form-horizontal')); ?>
			<fieldset>
				<legend><?php echo __('User Information'); ?></legend>
				<?php echo $this->Form->input('id'); ?>
				<?php echo $this->Form->input('fogbugz_id', array('label' => 'FogBugz ID', 'type' => 'text')); ?>
				<?php echo $this->Form->input('name'); ?>
				<?php echo $this->Form->input('email'); ?>
			</fieldset>
		<?php echo $this->Form->end(__('Submit')); ?>
	</div>
</div>
