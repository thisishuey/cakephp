<div class="modal fade" id="modal-<?php echo $modalPrefix; ?>-<?php echo $case['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo $case['id']; ?>: <?php echo $case['title']; ?></h4>
			</div>
			<div class="modal-body">
				<p><strong>Project:</strong> <?php echo $case['project']; ?></p>
				<?php if (isset($case['events'])): ?>
					<div class="panel-group" id="events-<?php echo $case['id']; ?>">
						<?php foreach ($case['events'] as $event): ?>
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<?php echo $this->Html->link($event['evtDescription'], '#collapse-event-' . $modalPrefix . '-' . $event['ixBugEvent'], array('escape' => false, 'data-toggle' => 'collapse', 'data-parent' => '#events-' . $case['id'])); ?>
										<small><?php echo $this->Time->format($event['dt'], '%B %e, %Y'); ?></small>
									</h4>
								</div>
								<div id="collapse-event-<?php echo $modalPrefix; ?>-<?php echo $event['ixBugEvent']; ?>" class="panel-collapse collapse">
									<div class="panel-body">
										<p><strong>Date:</strong> <?php echo $this->Time->nice($event['dt']); ?>
										<?php if ($event['sChanges'] !== ''): ?>
											<p><strong>Changes:</strong> <?php echo $event['sChanges']; ?></p>
										<?php endif; ?>
										<?php if (isset($event['sHtml']) && $event['sHtml'] !== ''): ?>
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
				<?php endif; ?>
			<div class="modal-footer">
				<?php echo $this->Html->link('Close', '#', array('class' => 'btn btn-default', 'data-dismiss' => 'modal')); ?>
				<?php echo $this->Html->link('Open Case in FogBugz', $this->Session->read('Auth.fogbugz_url') . '/default.asp?' . $case['id'], array('class' => 'btn btn-success', 'target' => '_blank')); ?>
			</div>
		</div>
	</div>
</div>
