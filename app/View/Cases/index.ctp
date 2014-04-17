<div class="page-header">
	<h2>
		Scrum: <?php echo $this->Time->format($date, '%m-%d-%Y'); ?>
		<?php echo $this->Html->link('Save Archive', array('scrum-' . $this->Time->format('now', '%Y-%m-%d') . '.json'), array('target' => '_blank', 'class' => 'btn btn-success btn-xs')); ?>
		<?php echo $this->Html->link('Load Archive', '#collapse-load-archive', array('class' => 'btn btn-primary btn-xs', 'data-toggle' => 'collapse')); ?>
	</h2>
</div>
<div id="collapse-load-archive" class="collapse">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<?php echo $this->Html->link('&times', '#collapse-load-archive', array('escape' => false, 'class' => 'close', 'data-toggle' => 'collapse')); ?>
			Load Archive
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('Archive', array('cherry' => 'form-horizontal', 'type' => 'file')); ?>
				<?php echo $this->Form->input('file', array('type' => 'file')); ?>
			<?php echo $this->Form->end(__('Submit')); ?>
		</div>
	</div>
</div>
<div class="panel-group" id="scrum-users">
	<?php foreach ($scrum as $id => $user): ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<?php echo $this->Html->link($user['User']['name'], '#collapse-user-' . $user['User']['id'], array('escape' => false, 'data-toggle' => 'collapse', 'data-parent' => '#scrum-users')); ?>
					<small><?php echo $this->Html->link('Last 7 Days', array('controller' => 'users', 'action' => 'view', $user['User']['id'])); ?></small>
				</h4>
			</div>
			<div id="collapse-user-<?php echo $user['User']['id']; ?>" class="panel-collapse collapse">
				<div class="panel-body">
					<h4>Completed</h4>
					<?php echo $this->element('Cases/list', array('projects' => $user['Completed'], 'modalPrefix' => $user['User']['id'], 'emptyText' => 'No cases completed')); ?>
					<h4>Working On</h4>
					<?php echo $this->element('Cases/list', array('projects' => $user['WorkingOn'], 'modalPrefix' => $user['User']['id'], 'emptyText' => 'No cases set to "Active (Dev)" or "Resolved (QA Review)"')); ?>
					<h4>Blockers</h4>
					<?php echo $this->element('Cases/list', array('projects' => $user['Blockers'], 'modalPrefix' => $user['User']['id'], 'emptyText' => 'No blockers')); ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
