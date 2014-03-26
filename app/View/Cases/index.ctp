<?php echo $this->Form->create('filter', array('cherry' => 'form-inline', 'class' => 'form-inline text-right')); ?>
	<?php echo $this->Form->input('user_id', array('label' => false)); ?>
<?php echo $this->Form->end(); ?>
<div class="page-header">
	<h4>Completed</h4>
</div>
<?php for ($i = 7; $i >= 0; $i--): ?>
	<?php $date = $this->Time->format('-' . $i . 'days'); ?>
	<h5>
		<?php if ($this->Time->format($date) === $this->Time->Format('now')): ?>
			Today <?php echo $this->Time->format($date, '<small>%B %e, %Y</small>'); ?>
		<?php else: ?>
			<?php echo $this->Time->format($date, '%A <small>%B %e, %Y</small>'); ?>
		<?php endif; ?>
	</h5>
	<ul>
		<?php if (!empty($completed[$date])): ?>
			<?php foreach ($completed[$date] as $id => $title): ?>
				<li><strong><?php echo $this->Html->link($id, $this->Session->read('Auth.fogbugz_url') . '/default.asp?' . $id, array('class' => 'text-success', 'target' => '_blank')); ?>:</strong> <?php echo $title; ?></li>
			<?php endforeach; ?>
		<?php else: ?>
			<li><strong class="text-warning">No cases resolved</strong></li>
		<?php endif; ?>
	</ul>
<?php endfor; ?>
<div class="page-header">
	<h4>Working On:</h4>
</div>
<ul>
	<?php if (!empty($workingOn)): ?>
		<?php foreach ($workingOn as $id => $title): ?>
			<li><strong class="text-success"><?php echo $this->Html->link($id, $this->Session->read('Auth.fogbugz_url') . '/default.asp?' . $id, array('class' => 'text-success', 'target' => '_blank')); ?>:</strong> <?php echo $title; ?></li>
		<?php endforeach; ?>
	<?php else: ?>
		<li><strong class="text-warning">No cases set to "Active (Dev)"</strong></li>
	<?php endif; ?>
</ul>
