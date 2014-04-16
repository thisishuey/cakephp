<ul>
	<?php if (!empty($projects)): ?>
		<?php foreach ($projects as $title => $cases): ?>
			<li>
				<h5><?php echo $title; ?></h5>
				<ul>
					<?php foreach ($cases as $case): ?>
						<li>
							<strong><?php echo $this->Html->link($case['id'], $this->Session->read('Auth.fogbugz_url') . '/default.asp?' . $case['id'], array('class' => 'text-success', 'target' => '_blank', 'data-toggle' => 'modal', 'data-target' => '#modal-' . $modalPrefix . '-' . $case['id'])); ?>:</strong>
							<?php echo $case['title']; ?>
							<strong>[<?php echo $case['elapsed']; ?>/<?php echo $case['estimate']; ?>]</strong>
							<?php echo $this->element('Cases/modal', compact('case', 'modalPrefix')); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</li>
		<?php endforeach; ?>
	<?php else: ?>
		<li><h5 class="text-warning"><?php echo $emptyText; ?></h5></li>
	<?php endif; ?>
</ul>
