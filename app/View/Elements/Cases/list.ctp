<ul>
	<?php if (!empty($projects)): ?>
		<?php foreach ($projects as $projectTitle => $cases): ?>
			<li>
				<h5><?php echo $projectTitle; ?></h5>
				<ul>
					<?php foreach ($cases as $case): ?>
						<li>
							<strong><?php echo $this->Html->link($case['id'], '#', array('class' => 'text-success', 'target' => '_blank', 'data-toggle' => 'modal', 'data-target' => '#modal-' . $case['id'])); ?>:</strong> <?php echo $case['title']; ?>
							<?php echo $this->element('Cases/modal', compact('case')); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</li>
		<?php endforeach; ?>
	<?php else: ?>
		<li><h5 class="text-warning"><?php echo $emptyText; ?></h5></li>
	<?php endif; ?>
</ul>
