<div class="row">
	<div class="col-md-3 sidebar">
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo __('Actions'); ?></div>
			<div class="list-group">
				<?php echo $this->Html->link(__('List Users'), array('action' => 'index'), array('class' => 'list-group-item')); ?>
				<?php echo $this->Html->link(__('View User'), array('action' => 'view', $user['User']['id']), array('class' => 'list-group-item active')); ?>
				<div class="list-group-item">
					<?php echo $this->Form->create('filter', array('cherry' => 'form-horizontal', 'data-url' => 'users/view/')); ?>
						<?php echo $this->Form->input('user_id', array('label' => false, 'before' => '<div class="col-md-12">', 'after' => '</div>')); ?>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 content">
		<h2><?php echo h($user['User']['name']); ?></h2>
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
		<div class="page-header">
			<h3>Completed</h3>
		</div>
		<?php foreach ($completed as $date => $completedDate): ?>
			<h4><?php echo $this->Time->format($date, $completedDate['dateFormat']); ?></h4>
			<?php echo $this->element('Cases/list', array('projects' => $completedDate['projects'], 'emptyText' => 'No cases resolved')); ?>
		<?php endforeach; ?>
		<div class="page-header">
			<h3>Working On:</h3>
		</div>
		<?php echo $this->element('Cases/list', array('projects' => $workingOn['projects'], 'emptyText' => 'No cases set to "Active (Dev)"')); ?>
	</div>
</div>
