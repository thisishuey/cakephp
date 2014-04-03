<div class="row">
	<div class="col-md-3 sidebar">
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
	<div class="col-md-9 content">
		<div class="page-header">
			<h2>
				<?php echo $user['User']['name']; ?>
				<small><?php echo $this->Html->link('Email', 'mailto:' . $user['User']['email']); ?></small>
			</h2>
		</div>
		<h3>Completed</h3>
		<ul>
			<?php foreach ($completed as $date => $completedDate): ?>
				<li>
					<h4><?php echo $this->Time->format($date, $completedDate['dateFormat']); ?></h4>
					<?php echo $this->element('Cases/list', array('projects' => $completedDate['projects'], 'emptyText' => 'No cases completed')); ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<h3>Working On:</h3>
		<?php echo $this->element('Cases/list', array('projects' => $workingOn['projects'], 'emptyText' => 'No cases set to "Active (Dev)"')); ?>
	</div>
</div>
