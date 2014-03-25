<div class="page-header">
	<h4>Completed</h4>
</div>
<?php foreach ($resolvedDates as $date => $resolvedDate): ?>
	<h5><?php echo $date; ?></h5>
	<ul>
		<?php if ($resolvedDate['cases']['@count'] > 0): ?>
			<?php foreach ($resolvedDate['cases']['case'] as $case): ?>
				<li><strong class="text-success"><?php echo $case['@ixBug']; ?>:</strong> <?php echo $case['sTitle']; ?></li>
			<?php endforeach; ?>
		<?php else: ?>
			<li><strong class="text-warning">No cases resolved</strong></li>
		<?php endif; ?>
	</ul>
<?php endforeach; ?>
<div class="page-header">
	<h4>Working On:</h4>
</div>
<ul>
	<?php if ($workingOn['cases']['@count'] > 0): ?>
		<?php foreach ($workingOn['cases']['case'] as $case): ?>
			<li><strong class="text-success"><?php echo $case['@ixBug']; ?>:</strong> <?php echo $case['sTitle']; ?></li>
		<?php endforeach; ?>
	<?php else: ?>
		<li><strong class="text-warning">No cases set to "Active (Dev)"</strong></li>
	<?php endif; ?>
</ul>
