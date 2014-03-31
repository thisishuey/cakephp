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
							<li>
								<strong><?php echo $this->Html->link($case['id'], $this->Session->read('Auth.fogbugz_url') . '/default.asp?' . $case['id'], array('class' => 'text-success', 'target' => '_blank', 'data-toggle' => 'modal', 'data-target' => '#modal-' . $case['id'])); ?>:</strong> <?php echo $case['title']; ?>
								<div class="modal fade" id="modal-<?php echo $case['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title" id="myModalLabel"><?php echo $case['id']; ?>: <?php echo $case['title']; ?></h4>
											</div>
											<div class="modal-body">
												<p><strong>Project:</strong> <?php echo $case['project']; ?></p>
												<div class="panel-group" id="accordion-<?php echo $case['id']; ?>">
													<?php foreach ($case['events'] as $event): ?>
														<div class="panel panel-default">
															<div class="panel-heading">
																<h4 class="panel-title">
																	<?php echo $this->Html->link($event['evtDescription'] . ' <small>' . $this->Time->format($event['dt'], '%m-%d-%Y') . '</small>', '#collapse-' . $event['ixBugEvent'], array('escape' => false, 'data-toggle' => 'collapse', 'data-parent' => '#accordion-' . $case['id'])); ?>
																</h4>
															</div>
															<div id="collapse-<?php echo $event['ixBugEvent']; ?>" class="panel-collapse collapse">
																<div class="panel-body">
																	<p><strong>Date:</strong> <?php echo $this->Time->nice($event['dt']); ?>
																	<?php if ($event['sChanges'] !== ''): ?>
																		<p><strong>Changes:</strong> <?php echo $event['sChanges']; ?></p>
																	<?php endif; ?>
																	<?php if ($event['sHtml'] !== ''): ?>
																		<p>
																			<strong>Notes:</strong><br>
																			<?php echo $event['sHtml']; ?>
																		</p>
																	<?php endif; ?>
																</div>
															</div>
														</div>
													<?php endforeach; ?>
												</div>
											<div class="modal-footer">
												<?php echo $this->Html->link('Close', '#', array('class' => 'btn btn-default', 'data-dismiss' => 'modal')); ?>
												<?php echo $this->Html->link('Open Case in FogBugz', $this->Session->read('Auth.fogbugz_url') . '/default.asp?' . $case['id'], array('class' => 'btn btn-success', 'target' => '_blank')); ?>
											</div>
										</div>
									</div>
								</div>
							</li>
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
