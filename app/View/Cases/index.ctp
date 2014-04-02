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
