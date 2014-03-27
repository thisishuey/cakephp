<div class="row">
	<div class="col-md-3 sidebar">
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo __('Actions'); ?></div>
			<div class="list-group">
				<?php echo $this->Html->link(__('List Users'), array('action' => 'index'), array('class' => 'list-group-item active')); ?>
				<?php echo $this->Html->link(__('Add User'), array('action' => 'add'), array('class' => 'list-group-item')); ?>
			</div>
		</div>
	</div>
	<div class="col-md-9 content">
		<h2><?php echo __('List Users'); ?></h2>
		<div class="table-responsive">
			<table class="table table-striped table-bordered">
				<tr>
					<th><?php echo $this->Paginator->sort('id'); ?></th>
					<th><?php echo $this->Paginator->sort('fogbugz_id'); ?></th>
					<th><?php echo $this->Paginator->sort('name'); ?></th>
					<th><?php echo $this->Paginator->sort('email'); ?></th>
					<th><?php echo $this->Paginator->sort('created'); ?></th>
					<th><?php echo $this->Paginator->sort('modified'); ?></th>
					<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				<?php foreach ($users as $user): ?>
					<tr>
						<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
						<td><?php echo h($user['User']['fogbugz_id']); ?>&nbsp;</td>
						<td><?php echo h($user['User']['name']); ?>&nbsp;</td>
						<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
						<td><?php echo $this->Time->timeAgoInWords($user['User']['created']); ?>&nbsp;</td>
						<td><?php echo $this->Time->timeAgoInWords($user['User']['modified']); ?>&nbsp;</td>
						<td class="actions">
							<div class="btn-group">
								<?php echo $this->Html->link('<span class="glyphicon glyphicon-list-alt"></span>', array('action' => 'view', $user['User']['id']), array('class' => 'btn btn-default btn-xs', 'title' => __('View'), 'escape' => false)); ?>
								<?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', array('action' => 'edit', $user['User']['id']), array('class' => 'btn btn-default btn-xs', 'title' => __('Edit'), 'escape' => false)); ?>
								<?php echo $this->Form->postLink('<span class="glyphicon glyphicon-trash"></span>', array('action' => 'delete', $user['User']['id']), array('class' => 'btn btn-default btn-xs', 'title' => __('Delete'), 'escape' => false, 'confirm' => __('Are you sure you want to delete this record (ID: %s)?', $user['User']['id']))); ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
		<?php echo $this->element('Cherry.paginator/counter'); ?>
		<?php echo $this->element('Cherry.paginator/navigation'); ?>
	</div>
</div>
