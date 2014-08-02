<div class="row">
	<div class="col-md-3 col-md-push-9 sidebar">
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo __('Switch User'); ?></div>
			<div class="list-group">
				<div class="list-group-item">
					<?php echo $this->Form->create('filter', array('cherry' => 'form-horizontal', 'data-url' => 'users/view/')); ?>
						<?php echo $this->Form->input('user_id', array('label' => false, 'before' => '<div class="col-md-12">', 'after' => '</div>')); ?>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-9 col-md-pull-3 content">
		<div class="page-header">
			<h2>
				<?php echo $user['User']['name']; ?>
				<small><?php echo $this->Html->link('Email', 'mailto:' . $user['User']['email']); ?></small>
			</h2>
		</div>
		<h3>Completed</h3>
		<ul>
			<?php foreach ($completed as $date => $completedDate): ?>
				<li><?php echo $this->element('Cases/list', array('projects' => $completedDate['projects'], 'modalPrefix' => $user['User']['id'], 'emptyText' => 'No cases completed', 'header' => $this->Time->format($date, $completedDate['dateFormat']))); ?></li>
			<?php endforeach; ?>
		</ul>
		<?php echo $this->element('Cases/list', array('projects' => $workingOn['projects'], 'modalPrefix' => $user['User']['id'], 'emptyText' => 'No cases set to "Active (Dev)" or "Resolved (QA Review)"', 'header' => 'Working On', 'headerWrapper' => 'h3')); ?>
		<?php echo $this->element('Cases/list', array('projects' => $blockers['projects'], 'modalPrefix' => $user['User']['id'], 'emptyText' => 'No blockers', 'header' => 'Blockers', 'headerWrapper' => 'h3')); ?>
	</div>
</div>
