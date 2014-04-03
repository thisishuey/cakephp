<div class="page-header">
	<h2>Scrum</h2>
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
					<?php echo $this->element('Cases/list', array('projects' => $user['Completed'], 'emptyText' => 'No cases completed')); ?>
					<h4>Working On</h4>
					<?php echo $this->element('Cases/list', array('projects' => $user['WorkingOn'], 'emptyText' => 'No cases set to "Active (Dev)"')); ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
