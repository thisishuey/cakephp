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
				<?php echo $this->Html->link(__('View User'), array('action' => 'view', $user['User']['id']), array('class' => 'list-group-item active')); ?>
				<?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id']), array('class' => 'list-group-item')); ?>
				<?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), array('class' => 'list-group-item', 'confirm' => __('Are you sure you want to delete this record (ID: %s)?', $user['User']['id']))); ?>
			</div>
		</div>
	</div>
	<div class="col-md-9 content">
		<h2><?php echo __('View User'); ?></h2>
		<dl class="dl-horizontal">
			<dt title="<?php echo __('ID'); ?>"><?php echo __('ID'); ?></dt>
			<dd><?php echo h($user['User']['id']); ?>&nbsp;</dd>
			<dt title="<?php echo __('FogBugz ID'); ?>"><?php echo __('FogBugz ID'); ?></dt>
			<dd><?php echo h($user['User']['fogbugz_id']); ?>&nbsp;</dd>
			<dt title="<?php echo __('Name'); ?>"><?php echo __('Name'); ?></dt>
			<dd><?php echo h($user['User']['name']); ?>&nbsp;</dd>
			<dt title="<?php echo __('Email'); ?>"><?php echo __('Email'); ?></dt>
			<dd><?php echo h($user['User']['email']); ?>&nbsp;</dd>
			<dt title="<?php echo __('Created'); ?>"><?php echo __('Created'); ?></dt>
			<dd><?php echo $this->Time->timeAgoInWords($user['User']['created']); ?>&nbsp;</dd>
			<dt title="<?php echo __('Modified'); ?>"><?php echo __('Modified'); ?></dt>
			<dd><?php echo $this->Time->timeAgoInWords($user['User']['modified']); ?>&nbsp;</dd>
		</dl>
	</div>
</div>
