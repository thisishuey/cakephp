<div class="cases <?php echo strtolower(Inflector::slug($header)); ?>" data-empty-text="<?php echo $emptyText; ?>">
	<?php if (!isset($headerWrapper)): ?>
		<?php $headerWrapper = 'h4'; ?>
	<?php endif; ?>
	<?php $openHeader = '<' . $headerWrapper . '>'; ?>
	<?php $closeHeader = '</' . $headerWrapper . '>'; ?>
	<?php echo $openHeader; ?><?php echo $header; ?><?php echo $closeHeader; ?>
	<ul>
		<?php if (!empty($projects)): ?>
			<?php foreach ($projects as $title => $cases): ?>
				<li>
					<h5><?php echo $title; ?></h5>
					<ul>
						<?php foreach ($cases as $case): ?>
							<?php $class = ''; ?>
							<?php if (isset($case['status'])): ?>
								<?php $class = strtolower(Inflector::slug($case['status'])); ?>
							<?php endif; ?>
							<?php $url = $this->Session->read('Auth.fogbugz_url') . '/default.asp?' . $case['id']; ?>
							<?php $target = '#modal-' . $modalPrefix . '-' . $case['id']; ?>
							<li class="<?php echo $class; ?>">
								<strong><?php echo $this->Html->link($case['id'], $url, array('class' => 'text-success', 'target' => '_blank', 'data-toggle' => 'modal', 'data-target' => $target)); ?>:</strong>
								<?php echo h($case['title']); ?>
								<strong>[<?php echo $case['elapsed']; ?>/<?php echo $case['estimate']; ?>]</strong>
								<?php echo $this->element('Cases/modal', compact('case', 'modalPrefix')); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</li>
			<?php endforeach; ?>
		<?php else: ?>
			<li class="empty"><h5 class="text-warning"><?php echo $emptyText; ?></h5></li>
		<?php endif; ?>
	</ul>
</div>
