<div class="page-header">
	<h4>Completed</h4>
</div>
<?php foreach ($completed as $date => $completedDate): ?>
	<h5><?php echo $date; ?></h5>
	<ul>
		<?php if ($completedDate['cases']['@count'] === '0'): ?>
			<li><strong class="text-warning">No cases resolved</strong></li>
		<?php elseif ($completedDate['cases']['@count'] === '1'): ?>
			<?php $case = $completedDate['cases']['case']; ?>
			<li><strong class="text-success"><?php echo $case['@ixBug']; ?>:</strong> <?php echo $case['sTitle']; ?></li>
		<?php else: ?>
			<?php foreach ($completedDate['cases']['case'] as $case): ?>
				<li><strong class="text-success"><?php echo $case['@ixBug']; ?>:</strong> <?php echo $case['sTitle']; ?></li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>
<?php endforeach; ?>
<div class="page-header">
	<h4>Working On:</h4>
</div>
<ul>
	<?php if ($workingOn['cases']['@count'] === '0'): ?>
		<li><strong class="text-warning">No cases set to "Active (Dev)"</strong></li>
	<?php elseif ($workingOn['cases']['@count'] === '1'): ?>
		<?php $case = $workingOn['cases']['case']; ?>
		<li><strong class="text-success"><?php echo $case['@ixBug']; ?>:</strong> <?php echo $case['sTitle']; ?></li>
	<?php else: ?>
		<?php foreach ($workingOn['cases']['case'] as $case): ?>
			<li><strong class="text-success"><?php echo $case['@ixBug']; ?>:</strong> <?php echo $case['sTitle']; ?></li>
		<?php endforeach; ?>
	<?php endif; ?>
</ul>
