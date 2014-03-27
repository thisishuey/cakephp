<?php echo $this->Form->create('filter', array('cherry' => 'form-inline', 'class' => 'form-inline text-right')); ?>
	<?php echo $this->Form->input('user_id', array('label' => false)); ?>
<?php echo $this->Form->end(); ?>
<div class="page-header">
	<h3>Completed</h3>
</div>
<?php for ($i = 7; $i >= 0; $i--): ?>
	<?php $date = $this->Time->format('-' . $i . 'days'); ?>
	<h4>
		<?php if ($this->Time->format($date) === $this->Time->Format('now')): ?>
			Today <?php echo $this->Time->format($date, '<small>%B %e, %Y</small>'); ?>
		<?php else: ?>
			<?php echo $this->Time->format($date, '%A <small>%B %e, %Y</small>'); ?>
		<?php endif; ?>
	</h4>
	<ul>
		<?php if (!empty($completed[$date])): ?>
			<?php foreach ($completed[$date] as $projectTitle => $cases): ?>
				<li>
					<h5><?php echo $projectTitle; ?></h5>
					<ul>
						<?php foreach ($cases as $case): ?>
							<li><strong><?php echo $this->Html->link($case['id'], $this->Session->read('Auth.fogbugz_url') . '/default.asp?' . $case['id'], array('class' => 'text-success', 'target' => '_blank')); ?>:</strong> <?php echo $case['title']; ?></li>
						<?php endforeach; ?>
					</ul>
				</li>
			<?php endforeach; ?>
		<?php else: ?>
			<li><h5 class="text-warning">No cases resolved</h5></li>
		<?php endif; ?>
	</ul>
<?php endfor; ?>
<div class="page-header">
	<h3>Working On:</h3>
</div>
<ul>
	<?php if (!empty($workingOn)): ?>
		<?php foreach ($workingOn as $projectTitle => $cases): ?>
			<li>
				<h5><?php echo $projectTitle; ?></h5>
				<ul>
					<?php foreach ($cases as $case): ?>
						<li><strong class="text-success"><?php echo $this->Html->link($case['id'], $this->Session->read('Auth.fogbugz_url') . '/default.asp?' . $case['id'], array('class' => 'text-success', 'target' => '_blank')); ?>:</strong> <?php echo $case['title']; ?></li>
					<?php endforeach; ?>
				</ul>
			</li>
		<?php endforeach; ?>
	<?php else: ?>
		<li><h5 class="text-warning">No cases set to "Active (Dev)"</h5></li>
	<?php endif; ?>
</ul>
